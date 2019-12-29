<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Period;
use App\Events\PeriodMoved;
use PHPUnit\Framework\TestCase;

class PeriodMovedTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectAttributes()
    {
        $event = $this->createEvent();

        $this->assertEquals([
            'id' => 123,
            'position' => 999,
        ], $event->broadcastWith());
    }

    protected function createEvent()
    {
        return new PeriodMoved(
            new Period(['id' => 123]),
            999
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.' . $event->period->history_id;
    }
}
