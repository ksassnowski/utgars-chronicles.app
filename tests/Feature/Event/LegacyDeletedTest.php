<?php declare(strict_types=1);

namespace Tests\Feature\Event;

use App\History;
use Tests\TestCase;
use App\Events\LegacyDeleted;
use Tests\Unit\Event\BroadcastingEventTest;

class LegacyDeletedTest extends TestCase
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
        return new LegacyDeleted(123, new History(['id' => 999]));
    }

    protected function getChannelName($event): string
    {
        return 'history.999';
    }
}
