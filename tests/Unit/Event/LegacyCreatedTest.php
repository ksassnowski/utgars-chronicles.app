<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Legacy;
use App\Events\LegacyCreated;
use PHPUnit\Framework\TestCase;

class LegacyCreatedTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectAttributes()
    {
        $this->assertEquals([
            'legacy' => [
                'id' => 999,
                'name' => '::legacy-name::',
            ],
            'history' => 123,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new LegacyCreated(
            new Legacy([
                'id' => 999,
                'name' => '::legacy-name::',
                'history_id' => 123,
            ])
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
