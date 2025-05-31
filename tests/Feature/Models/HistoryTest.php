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

namespace Tests\Feature\Models;

use App\History;
use Tests\TestCase;

/**
 * @internal
 */
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
