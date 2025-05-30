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

use App\Events\LegacyUpdated;
use App\Legacy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LegacyUpdatedEventTest extends TestCase
{
    use BroadcastingEventTestSuite;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'id' => 999,
            'name' => '::new-name::',
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new LegacyUpdated(
            new Legacy([
                'id' => 999,
                'name' => '::new-name::',
                'history_id' => 123,
            ]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
