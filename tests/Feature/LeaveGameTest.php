<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveGameTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest;

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'leave game' => ['delete', '/games/1'],
        ];
    }

    /** @test */
    public function leaveGame(): void
    {
        /** @var History $game */
        $game = History::factory()->create();
        /** @var User $player */
        $player = User::factory()->create();
        $game->addPlayer($player);

        $response = $this->actingAs($player)->deleteJson(route('user.games.leave', $game));

        $response->assertRedirect(route('home'));
        $this->assertFalse($player->refresh()->games->contains('id', $game->id));
    }

    /** @test */
    public function canOnlyLeaveGamesOfWhichYouAreAPlayer(): void
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
