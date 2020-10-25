<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\History;
use Tests\TestCase;
use App\AnonymousPlayer;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MicroscopeMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRoutes();
    }

    /** @test */
    public function ownerOfHistoryCanAccessRoute(): void
    {
        $history = History::factory()->create();

        $this->actingAs($history->owner)
            ->get('/history/' . $history->id . '/test')
            ->assertOk();
    }

    /** @test */
    public function otherUserCannotAccessRoute(): void
    {
        $user = User::factory()->create();
        $history = History::factory()->create();

        $this->actingAs($user)
            ->get('/history/' . $history->id . '/test')
            ->assertForbidden();
    }

    /** @test */
    public function playerOfHistoryCanAccessRoute(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $user = User::factory()->create();
        $history->addPlayer($user);

        $this->actingAs($user)
            ->get('/history/' . $history->id . '/test')
            ->assertOk();
    }

    /** @test */
    public function fallThroughIfRouteDoesNotContainHistory(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/derp')
            ->assertOk();
    }

    /** @test */
    public function invitedGuestsCanAccessRouteForPublicHistory()
    {
        $history = History::factory()->public()->create();

        (new AnonymousPlayer('::id::'))->joinGame($history);

        $this
            ->get('/history/' . $history->id . '/test')
            ->assertOk();
    }

    /** @test */
    public function unauthenticatedUserCannotAccessRouteForPrivateHistory(): void
    {
        $history = History::factory()->create();

        $this->get('/history/' . $history->id . '/test')
            ->assertForbidden();
    }

    /** @test */
    public function guestsCannotAccessRouteForPrivateGameEvenIfTheyHavePreviouslyJoined(): void
    {
        $history = History::factory()->create();

        (new AnonymousPlayer('::id::'))->joinGame($history);

        $this->get('/history/' . $history->id . '/test')
            ->assertForbidden();
    }

    private function setUpRoutes()
    {
        Route::get('/history/{history}/test', function (History $history) {
            return response('', 200);
        })->middleware(['web', 'microscope']);

        Route::get('/derp', function () {
            return response('', 200);
        })->middleware(['web', 'microscope']);
    }
}
