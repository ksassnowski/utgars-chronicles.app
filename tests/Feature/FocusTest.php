<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Focus;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use App\Events\FocusDefined;
use App\Events\FocusDeleted;
use App\Events\FocusUpdated;
use Tests\ValidateRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Http\Requests\History\DefineFocusRequest;
use App\Http\Requests\History\UpdateFocusRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Focus\UpdateFocusController;
use App\Http\Controllers\History\DefineFocusController;

class FocusTest extends TestCase
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

    /** @test */
    public function defineFocusForHistory(): void
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
        $this->assertTrue($history->foci->contains('name', '::focus-name::'));
        Event::assertDispatched(
            FocusDefined::class,
            fn (FocusDefined $e) => $e->history->id === $history->id && $e->focus->name === '::focus-name::'
        );
    }

    /** @test */
    public function updateFocus()
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
        $this->assertEquals('::new-name::', $focus->refresh()->name);
        Event::assertDispatched(
            FocusUpdated::class,
            fn (FocusUpdated $e) => $e->focus->name === '::new-name::' && $e->focus->id === $focus->id
        );
    }

    /** @test */
    public function deleteFocus(): void
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
            fn (FocusDeleted $e) => $e->history->id === $history->id && $e->focusId === $focus->id
        );
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update focus' => [
                'put',
                fn () => Focus::factory()->create(),
                fn (History $history, Focus $focus) => route('focus.update', [$history, $focus]),
            ],
            'delete focus' => [
                'delete',
                fn () => Focus::factory()->create(),
                fn (History $history, Focus $focus) => route('focus.delete', [$history, $focus]),
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
