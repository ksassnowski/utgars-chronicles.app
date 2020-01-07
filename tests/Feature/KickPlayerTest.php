<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KickPlayerTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest;

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'kick player' => ['delete', '/histories/1/players/1'],
        ];
    }

    /** @test */
    public function kickPlayer(): void
    {
        /** @var History $history */
        $history = factory(History::class)->create();
        /** @var User $kickedPlayer */
        $kickedPlayer = factory(User::class)->create();
        $history->addPlayer($kickedPlayer);
        $this->assertTrue($history->players->contains('id', $kickedPlayer->id));

        $response = $this->actingAs($history->owner)->deleteJson(route('history.players.kick', [$history, $kickedPlayer]));

        $response->assertRedirect(route('history.show', $history));
        $this->assertFalse($history->refresh()->players->contains('id', $kickedPlayer->id));
    }

    /** @test */
    public function onlyOwnerCanKickPlayers(): void
    {
        /** @var History $history */
        $history = factory(History::class)->create();
        /** @var User $kickedPlayer */
        $player = factory(User::class)->create();
        $history->addPlayer($player);

        $response = $this->actingAs($player)->deleteJson(route('history.players.kick', [$history, $player]));
        $response->assertForbidden();

        $otherUser = factory(User::class)->create();

        $response = $this->actingAs($otherUser)->deleteJson(route('history.players.kick', [$history, $player]));
        $response->assertForbidden();
    }
}
