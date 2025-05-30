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

use App\Events\HistorySeedUpdated;
use App\History;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HistorySeedUpdatedTest extends TestCase
{
    use BroadcastingEventTestSuite;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'name' => '::new-name::',
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent(): HistorySeedUpdated
    {
        return new HistorySeedUpdated(
            new History([
                'id' => 123,
                'name' => '::new-name::',
            ]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
