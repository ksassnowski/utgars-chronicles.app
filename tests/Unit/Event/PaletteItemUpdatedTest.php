<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\History;
use PHPUnit\Framework\TestCase;
use App\Events\PaletteItemUpdated;

class PaletteItemUpdatedTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectAttributes(): void
    {
        $this->assertEquals([
            'id' => 123,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new PaletteItemUpdated(
            123,
            new History(['id' => 999]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.' . $event->history->id;
    }
}
