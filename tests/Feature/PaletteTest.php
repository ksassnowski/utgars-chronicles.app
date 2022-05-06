<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature;

use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemDeleted;
use App\Events\PaletteItemUpdated;
use App\History;
use App\Http\Controllers\PaletteController;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;
use App\Palette;
use App\PaletteType;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;

/**
 * @internal
 */
final class PaletteTest extends TestCase
{
    use RefreshDatabase;
    use ValidateRoutesTest;
    use ScopedRouteTest;
    use GameRouteTest;

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

    public function testAddToPalette(): void
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
            static function (ItemAddedToPalette $event) {
                return '::entry-name::' === $event->item->name
                    && PaletteType::YES === $event->item->type;
            },
        );
    }

    public function testEditPaletteItem(): void
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
        self::assertEquals('::new-name::', $item->name);
        self::assertEquals(PaletteType::NO, $item->type);
        Event::assertDispatched(
            PaletteItemUpdated::class,
            static fn (PaletteItemUpdated $event) => $event->item->id === $item->id,
        );
    }

    public function testDeletePaletteItem(): void
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
            static fn (PaletteItemDeleted $event) => $event->item->id === $item->id && $event->history->id === $history->id,
        );
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update palette' => [
                'put',
                static fn () => Palette::factory()->create(),
                static fn (History $history, Palette $palette) => route('palette.update', [$history, $palette]),
            ],
            'delete palette' => [
                'delete',
                static fn () => Palette::factory()->create(),
                static fn (History $history, Palette $palette) => route('palette.delete', [$history, $palette]),
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.palette.store'];

        yield ['palette.update'];

        yield ['palette.delete'];
    }
}
