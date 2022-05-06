<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\History;
use App\MicroscopePlayer;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait MicroscopePlayerTest
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function isPlayerInGameAfterJoining(): void
    {
        $history = History::factory()->create();
        $player = $this->getPlayerInstance();

        $player->joinGame($history);

        $this->assertTrue($player->isPlayer($history));
    }

    /**
     * @test
     */
    public function isNotAPlayerInGamesTheyHaventJoined(): void
    {
        $history = History::factory()->create();
        $player = $this->getPlayerInstance();

        $this->assertFalse($player->isPlayer($history));
    }

    /**
     * @test
     */
    public function isNotAPlayerInDifferentHistory(): void
    {
        [$history, $otherHistory] = History::factory(2)->create();
        $player = $this->getPlayerInstance();

        $player->joinGame($history);

        $this->assertFalse($player->isPlayer($otherHistory));
    }

    abstract protected function getPlayerInstance(): MicroscopePlayer;
}
