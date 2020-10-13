<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\History;
use Tests\TestCase;
use App\Events\FocusDefined;
use App\Events\FocusDeleted;
use App\Events\FocusUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FocusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @test
     * @dataProvider routeProvider
     */
    public function authenticationTest(string $method, string $uri): void
    {
        $method = "${method}Json";

        /** @var TestResponse $response */
        $response = $this->$method($uri);

        $response->assertUnauthorized();
    }

    public function routeProvider()
    {
        yield from [
            'define focus' => ['post', '/histories/1/focus'],
            'edit focus' => ['put', '/focus/1'],
            'delete focus' => ['delete', '/focus/1'],
        ];
    }

    /** @test */
    public function defineFocusForHistory(): void
    {
        /** @var History $history */
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->postJson(route('history.focus.define', $history), [
            'name' => '::focus-name::',
        ]);

        $response->assertStatus(201);
        $history->refresh();
        $this->assertTrue($history->focus->contains('name', '::focus-name::'));
        Event::assertDispatched(
            FocusDefined::class,
            fn (FocusDefined $e) => $e->history->id === $history->id && $e->focus->name === '::focus-name::'
        );
    }

    /** @test */
    public function nameIsRequired()
    {
        /** @var History $history */
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->postJson(route('history.focus.define', $history), []);

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function onlyPlayersCanDefineFoci()
    {
        $player = User::factory()->create();
        /** @var History $history */
        $history = History::factory()->create();
        $history->addPlayer($player);

        $response = $this->actingAs($player)->postJson(route('history.focus.define', $history), [
            'name' => '::focus-name::',
        ]);
        $response->assertStatus(201);

        $notAPlayer = User::factory()->create();
        $response = $this->actingAs($notAPlayer)->postJson(route('history.focus.define', $history), [
            'name' => '::other-focus-name::',
        ]);
        $response->assertForbidden();
    }

    /** @test */
    public function updateFocus()
    {
        /** @var History $history */
        $history = History::factory()->create();
        $focus = $history->defineFocus('::old-name::');

        $response = $this->actingAs($history->owner)->putJson(route('focus.update', $focus), [
            'name' => '::new-name::',
        ]);

        $response->assertOk();
        $this->assertEquals('::new-name::', $focus->refresh()->name);
        Event::assertDispatched(
            FocusUpdated::class,
            fn (FocusUpdated $e) => $e->focus->name === '::new-name::' && $e->focus->id === $focus->id
        );
    }

    /** @test */
    public function nameIsRequiredWhenUpdating(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $focus = $history->defineFocus('::old-name::');

        $response = $this->actingAs($history->owner)->putJson(route('focus.update', $focus));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function onlyPlayerCanUpdateFoci(): void
    {
        $player = User::factory()->create();
        /** @var History $history */
        $history = History::factory()->create();
        $history->addPlayer($player);
        $focus = $history->defineFocus('::old-focus::');

        $response = $this->actingAs($player)->putJson(route('focus.update', $focus), [
            'name' => '::new-focus::',
        ]);
        $response->assertStatus(200);

        $notAPlayer = User::factory()->create();
        $response = $this->actingAs($notAPlayer)->putJson(route('focus.update', $focus), [
            'name' => '::other-focus-name::',
        ]);
        $response->assertForbidden();
    }

    /** @test */
    public function deleteFocus(): void
    {
        $this->withoutExceptionHandling();
        /** @var History $history */
        $history = History::factory()->create();
        $focus = $history->defineFocus('::focus-name::');

        $response = $this->actingAs($history->owner)->deleteJson(route('focus.delete', $focus));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('foci', ['id' => $focus->id]);
        Event::assertDispatched(
            FocusDeleted::class,
            fn (FocusDeleted $e) => $e->history->id === $history->id && $e->focusId === $focus->id
        );
    }

    /** @test */
    public function onlyPlayerCanDeleteFoci()
    {
        $player = User::factory()->create();
        /** @var History $history */
        $history = History::factory()->create();
        $history->addPlayer($player);
        $focus = $history->defineFocus('::old-focus::');

        $notAPlayer = User::factory()->create();
        $response = $this->actingAs($notAPlayer)->deleteJson(route('focus.delete', $focus));
        $response->assertForbidden();

        $response = $this->actingAs($player)->deleteJson(route('focus.delete', $focus));
        $response->assertStatus(204);
    }
}
