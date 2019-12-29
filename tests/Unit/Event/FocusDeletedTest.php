<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\History;
use App\Events\FocusDeleted;
use PHPUnit\Framework\TestCase;

class FocusDeletedTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectData(): void
    {
        $event = $this->createEvent();

        $this->assertEquals(['id' => $event->focusId], $event->broadcastWith());
    }

    protected function createEvent()
    {
        return new FocusDeleted(
            new History(['id' => 123]),
            5
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.' . $event->history->id;
    }
}
