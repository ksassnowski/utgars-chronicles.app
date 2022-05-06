<?php

declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Events\PaletteItemUpdated;
use App\Palette;
use App\PaletteType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PaletteItemUpdatedTest extends TestCase
{
    use BroadcastingEventTest;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'id' => 123,
            'name' => '::new-name::',
            'type' => PaletteType::YES,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new PaletteItemUpdated(
            new Palette([
                'id' => 123,
                'name' => '::new-name::',
                'type' => PaletteType::YES,
                'history_id' => 999,
            ]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.999';
    }
}
