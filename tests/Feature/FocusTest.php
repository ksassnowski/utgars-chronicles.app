<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Events\FocusDefined;
use App\Events\FocusDeleted;
use App\Events\FocusUpdated;
use App\Focus;
use App\History;
use App\Http\Controllers\Focus\UpdateFocusController;
use App\Http\Controllers\History\DefineFocusController;
use App\Http\Requests\History\DefineFocusRequest;
use App\Http\Requests\History\UpdateFocusRequest;
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
final class FocusTest extends TestCase
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
            'create focus' => [
                DefineFocusController::class,
                '__invoke',
                DefineFocusRequest::class,
            ],
            'update focus' => [
                UpdateFocusController::class,
                '__invoke',
                UpdateFocusRequest::class,
            ],
        ];
    }

    public function testDefineFocusForHistory(): void
    {
        /** @var History $history */
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)
            ->postJson(route('history.focus.define', $history), [
                'name' => '::focus-name::',
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $history->refresh();
        self::assertTrue($history->foci->contains('name', '::focus-name::'));
        Event::assertDispatched(
            FocusDefined::class,
            static fn (FocusDefined $e) => $e->history->id === $history->id && '::focus-name::' === $e->focus->name,
        );
    }

    public function testUpdateFocus(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $focus = $history->defineFocus('::old-name::');

        $response = $this->actingAs($history->owner)
            ->putJson(route('focus.update', [$history, $focus]), [
                'name' => '::new-name::',
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        self::assertEquals('::new-name::', $focus->refresh()->name);
        Event::assertDispatched(
            FocusUpdated::class,
            static fn (FocusUpdated $e) => '::new-name::' === $e->focus->name && $e->focus->id === $focus->id,
        );
    }

    public function testDeleteFocus(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $focus = $history->defineFocus('::focus-name::');

        $response = $this->actingAs($history->owner)
            ->deleteJson(route('focus.delete', [$history, $focus]));

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('foci', ['id' => $focus->id]);
        Event::assertDispatched(
            FocusDeleted::class,
            static fn (FocusDeleted $e) => $e->history->id === $history->id && $e->focusId === $focus->id,
        );
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update focus' => [
                'put',
                static fn () => Focus::factory()->create(),
                static fn (History $history, Focus $focus) => route('focus.update', [$history, $focus]),
            ],
            'delete focus' => [
                'delete',
                static fn () => Focus::factory()->create(),
                static fn (History $history, Focus $focus) => route('focus.delete', [$history, $focus]),
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.focus.define'];

        yield ['focus.update'];

        yield ['focus.delete'];
    }
}
