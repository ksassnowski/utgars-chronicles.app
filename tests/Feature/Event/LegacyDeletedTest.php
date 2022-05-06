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

namespace Tests\Feature\Event;

use App\Events\LegacyDeleted;
use App\History;
use Tests\TestCase;
use Tests\Unit\Event\BroadcastingEventTest;

/**
 * @internal
 */
final class LegacyDeletedTest extends TestCase
{
    use BroadcastingEventTest;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'id' => 123,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new LegacyDeleted(123, new History(['id' => 999]));
    }

    protected function getChannelName($event): string
    {
        return 'history.999';
    }
}
