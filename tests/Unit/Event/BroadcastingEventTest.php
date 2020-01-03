<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

trait BroadcastingEventTest
{
    /** @test */
    public function implementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ShouldBroadcastNow::class, $this->createEvent());
    }

    /** @test */
    public function broadcastOnCorrectChannel()
    {
        $event = $this->createEvent();

        $this->assertEquals(
            new PresenceChannel($this->getChannelName($event)),
            $event->broadcastOn(),
        );
    }

    abstract protected function createEvent();

    abstract protected function getChannelName($event): string;
}
