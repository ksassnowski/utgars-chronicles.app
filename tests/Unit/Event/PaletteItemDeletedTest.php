<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\History;
use PHPUnit\Framework\TestCase;
use App\Events\PaletteItemDeleted;

class PaletteItemDeletedTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectAttributes(): void
    {
        $this->assertEquals([
            'id' => 123,
            'history' => 999,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new PaletteItemDeleted(
            123,
            new History(['id' => 999])
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.999';
    }
}
