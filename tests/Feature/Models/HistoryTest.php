<?php

namespace Tests\Feature\Models;

use App\History;
use Tests\TestCase;

final class HistoryTest extends TestCase
{
    public function testDontCreateEchoGameSettingsIfGameModeIsNotEcho(): void
    {
        $history = History::factory()->create();

        self::assertFalse($history->echoGameSettings()->exists());
    }

    public function testCreateEchoGameSettingsGameModeIsEcho(): void
    {
        $history = History::factory()
            ->echo()
            ->create();

        self::assertTrue($history->echoGameSettings()->exists());
    }
}
