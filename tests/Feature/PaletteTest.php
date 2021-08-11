<?php declare(strict_types=1);

namespace Tests\Feature;

use Generator;
use App\History;
use App\Palette;
use Tests\TestCase;
use App\PaletteType;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\ValidateRoutesTest;
use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemDeleted;
use App\Events\PaletteItemUpdated;
use Illuminate\Support\Facades\Event;
use App\Http\Controllers\PaletteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;

class PaletteTest extends TestCase
{
    use RefreshDatabase, ValidateRoutesTest, ScopedRouteTest, GameRouteTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function validationProvider(): Generator
    {
        yield from [
            'add to palette' => [PaletteController::class, 'store', CreatePaletteItemRequest::class],
            'edit palette item' => [PaletteController::class, 'update', UpdatePaletteItemRequest::class],
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

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
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

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
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

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('palettes', [
            'id' => $item->id,
        ]);
        Event::assertDispatched(
            PaletteItemDeleted::class,
            fn (PaletteItemDeleted $event) => $event->itemId === $item->id && $event->history->id === $history->id
        );
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update palette' => [
                'put',
                fn () => Palette::factory()->create(),
                fn (History $history, Palette $palette) => route('palette.update', [$history, $palette]),
            ],
            'delete palette' => [
                'delete',
                fn () => Palette::factory()->create(),
                fn (History $history, Palette $palette) => route('palette.delete', [$history, $palette]),
            ]
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.palette.store'];
        yield ['palette.update'];
        yield ['palette.delete'];
    }
}
