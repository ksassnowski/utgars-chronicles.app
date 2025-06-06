<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature;

use App\History;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedRoutesTest;
use Tests\TestCase;

/**
 * @internal
 */
final class LeaveGameTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoutesTest;

    public static function authenticatedRoutesProvider(): \Generator
    {
        yield from [
            'leave game' => ['delete', '/games/1'],
        ];
    }

    public function testLeaveGame(): void
    {
        /** @var History $game */
        $game = History::factory()->create();

        /** @var User $player */
        $player = User::factory()->create();
        $game->addPlayer($player);

        $response = $this->actingAs($player)->deleteJson(route('user.games.leave', $game));

        $response->assertRedirect(route('home'));
        self::assertFalse($player->refresh()->games->contains('id', $game->id));
    }

    public function testCanOnlyLeaveGamesOfWhichYouAreAPlayer(): void
    {
        /** @var History $game */
        $game = History::factory()->create();

        /** @var User $player */
        $player = User::factory()->create();

        $response = $this->actingAs($player)->deleteJson(route('user.games.leave', $game));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'You are not a player of this game.');
    }
}
