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
