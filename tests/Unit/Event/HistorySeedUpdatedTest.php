<?php

declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Events\HistorySeedUpdated;
use App\History;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HistorySeedUpdatedTest extends TestCase
{
    use BroadcastingEventTest;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'name' => '::new-name::',
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent(): HistorySeedUpdated
    {
        return new HistorySeedUpdated(
            new History([
                'id' => 123,
                'name' => '::new-name::',
            ]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
