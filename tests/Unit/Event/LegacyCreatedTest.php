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

namespace Tests\Unit\Event;

use App\Events\LegacyCreated;
use App\Legacy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LegacyCreatedTest extends TestCase
{
    use BroadcastingEventTestSuite;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'id' => 999,
            'name' => '::legacy-name::',
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new LegacyCreated(
            new Legacy([
                'id' => 999,
                'name' => '::legacy-name::',
                'history_id' => 123,
            ]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
