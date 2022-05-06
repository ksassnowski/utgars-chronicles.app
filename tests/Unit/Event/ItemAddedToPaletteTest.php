<?php

declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Events\ItemAddedToPalette;
use App\Palette;
use App\PaletteType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ItemAddedToPaletteTest extends TestCase
{
    use BroadcastingEventTest;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'id' => 999,
            'name' => '::entry-name::',
            'type' => PaletteType::YES,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new ItemAddedToPalette(
            new Palette([
                'id' => 999,
                'name' => '::entry-name::',
                'type' => PaletteType::YES,
                'history_id' => 123,
            ]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.123';
    }
}
