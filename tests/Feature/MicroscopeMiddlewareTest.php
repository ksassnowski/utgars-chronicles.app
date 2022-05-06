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

use App\AnonymousPlayer;
use App\History;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * @internal
 */
final class MicroscopeMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRoutes();
    }

    public function testOwnerOfHistoryCanAccessRoute(): void
    {
        $history = History::factory()->create();

        $this->actingAs($history->owner)
            ->get('/history/' . $history->id . '/test')
            ->assertOk();
    }

    public function testOtherUserCannotAccessRoute(): void
    {
        $user = User::factory()->create();
        $history = History::factory()->create();

        $this->actingAs($user)
            ->get('/history/' . $history->id . '/test')
            ->assertForbidden();
    }

    public function testPlayerOfHistoryCanAccessRoute(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $user = User::factory()->create();
        $history->addPlayer($user);

        $this->actingAs($user)
            ->get('/history/' . $history->id . '/test')
            ->assertOk();
    }

    public function testFallThroughIfRouteDoesNotContainHistory(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/derp')
            ->assertOk();
    }

    public function testInvitedGuestsCanAccessRouteForPublicHistory(): void
    {
        $history = History::factory()->public()->create();

        (new AnonymousPlayer('::id::'))->joinGame($history);

        $this
            ->get('/history/' . $history->id . '/test')
            ->assertOk();
    }

    public function testUnauthenticatedUserCannotAccessRouteForPrivateHistory(): void
    {
        $history = History::factory()->create();

        $this->get('/history/' . $history->id . '/test')
            ->assertForbidden();
    }

    public function testGuestsCannotAccessRouteForPrivateGameEvenIfTheyHavePreviouslyJoined(): void
    {
        $history = History::factory()->create();

        (new AnonymousPlayer('::id::'))->joinGame($history);

        $this->get('/history/' . $history->id . '/test')
            ->assertForbidden();
    }

    private function setUpRoutes(): void
    {
        Route::get('/history/{history}/test', static function (History $history) {
            return response('', 200);
        })->middleware(['web', 'microscope']);

        Route::get('/derp', static function () {
            return response('', 200);
        })->middleware(['web', 'microscope']);
    }
}
