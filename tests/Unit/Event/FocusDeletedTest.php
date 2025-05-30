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

use App\Events\FocusDeleted;
use App\History;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FocusDeletedTest extends TestCase
{
    use BroadcastingEventTestSuite;

    public function testBroadcastCorrectData(): void
    {
        $event = $this->createEvent();

        self::assertEquals(['id' => $event->focusId], $event->broadcastWith());
    }

    protected function createEvent()
    {
        return new FocusDeleted(
            new History(['id' => 123]),
            5,
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.' . $event->history->id;
    }
}
