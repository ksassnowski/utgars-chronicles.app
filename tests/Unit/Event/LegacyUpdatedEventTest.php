<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Legacy;
use App\Events\LegacyUpdated;
use PHPUnit\Framework\TestCase;

class LegacyUpdatedEventTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectAttributes(): void
    {
        $this->assertEquals([
            'id' => 999,
            'name' => '::new-name::',
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new LegacyUpdated(
            new Legacy([
                'id' => 999,
                'name' => '::new-name::',
                'history_id' => 123,
            ])
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
