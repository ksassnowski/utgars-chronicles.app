<?php

declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Events\FocusDeleted;
use App\History;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FocusDeletedTest extends TestCase
{
    use BroadcastingEventTest;

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
