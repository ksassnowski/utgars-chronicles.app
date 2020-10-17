<?php declare(strict_types=1);

namespace Tests\Feature;

use Generator;
use App\History;
use App\Palette;
use Tests\TestCase;
use App\PaletteType;
use Tests\ValidateRoutesTest;
use Tests\AuthorizeHistoryTest;
use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemDeleted;
use App\Events\PaletteItemUpdated;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Http\Controllers\PaletteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;

class PaletteTest extends TestCase
{
    use RefreshDatabase, AuthorizeHistoryTest, AuthenticatedRoutesTest, ValidateRoutesTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'add to palette' => ['post', '/histories/1/palette'],
            'edit palette item' => ['put', '/histories/1/palette/1'],
            'delete palette item' => ['delete', '/histories/1/palette/1'],
        ];
    }

    public function validationProvider(): Generator
    {
        yield from [
            'add to palette' => [PaletteController::class, 'store', CreatePaletteItemRequest::class],
            'edit palette item' => [PaletteController::class, 'update', UpdatePaletteItemRequest::class],
        ];
    }

    public function authorizationProvider(): Generator
    {
        yield from [
            'add item to palette' => [
                ['name' => '::entry-name::', 'type' => PaletteType::NO],
                fn (History $history) => route('history.palette.store', $history),
                'post',
                201,
            ],
            'edit palette item' => [
                ['name' => '::entry-name::', 'type' => PaletteType::NO],
                fn (Palette $palette) => route('palette.update', [$palette->history, $palette]),
                'put',
                200,
                fn (History $history) => Palette::factory()->create(['history_id' => $history->id]),
            ],
            'delete palette item' => [
                [],
                fn (Palette $palette) => route('palette.delete', [$palette->history, $palette]),
                'delete',
                204,
                fn (History $history) => Palette::factory()->create(['history_id' => $history->id]),
            ],
        ];
    }

    /** @test */
    public function addToPalette()
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->postJson(route('history.palette.store', $history), [
            'name' => '::entry-name::',
            'type' => PaletteType::YES,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('palettes', [
            'history_id' => $history->id,
            'name' => '::entry-name::',
            'type' => PaletteType::YES,
        ]);
        Event::assertDispatched(
            ItemAddedToPalette::class,
            function (ItemAddedToPalette $event) use ($history) {
                return $event->item->name === '::entry-name::'
                    && $event->item->type === PaletteType::YES;
            }
        );
    }

    /** @test */
    public function editPaletteItem(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $item = $history->addToPalette('::old-name::', PaletteType::YES);

        $response = $this->actingAs($history->owner)->putJson(route('palette.update', [$history, $item]), [
            'name' => '::new-name::',
            'type' => PaletteType::NO,
        ]);

        $response->assertOk();
        $item->refresh();
        $this->assertEquals('::new-name::', $item->name);
        $this->assertEquals(PaletteType::NO, $item->type);
        Event::assertDispatched(
            PaletteItemUpdated::class,
            fn (PaletteItemUpdated $event) => $event->item->id === $item->id
        );
    }

    /** @test */
    public function deletePaletteItem()
    {
        $history = History::factory()->create();
        $item = $history->addToPalette('::old-name::', PaletteType::YES);

        $response = $this->actingAs($history->owner)
            ->deleteJson(route('palette.delete', [$history, $item]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('palettes', [
            'id' => $item->id,
        ]);
        Event::assertDispatched(
            PaletteItemDeleted::class,
            fn (PaletteItemDeleted $event) => $event->itemId === $item->id && $event->history->id === $history->id
        );
    }
}
