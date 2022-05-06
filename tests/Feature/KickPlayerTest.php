<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\History;
use App\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthenticatedRoutesTest;
use Tests\TestCase;

/**
 * @internal
 */
final class KickPlayerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoutesTest;

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'kick player' => ['delete', '/histories/1/players/1'],
        ];
    }

    public function testKickPlayer(): void
    {
        /** @var History $history */
        $history = History::factory()->create();

        /** @var User $kickedPlayer */
        $kickedPlayer = User::factory()->create();
        $history->addPlayer($kickedPlayer);
        self::assertTrue($history->players->contains('id', $kickedPlayer->id));

        $response = $this->actingAs($history->owner)->deleteJson(route('history.players.kick', [$history, $kickedPlayer]));

        $response->assertRedirect(route('history.show', $history));
        self::assertFalse($history->refresh()->players->contains('id', $kickedPlayer->id));
    }

    public function testOnlyOwnerCanKickPlayers(): void
    {
        /** @var History $history */
        $history = History::factory()->create();

        /** @var User $kickedPlayer */
        $player = User::factory()->create();
        $history->addPlayer($player);

        $response = $this->actingAs($player)->deleteJson(route('history.players.kick', [$history, $player]));
        $response->assertForbidden();

        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->deleteJson(route('history.players.kick', [$history, $player]));
        $response->assertForbidden();
    }
}
