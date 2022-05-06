<?php

declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Events\PaletteItemDeleted;
use App\History;
use App\Palette;
use App\PaletteType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PaletteItemDeletedTest extends TestCase
{
    use BroadcastingEventTest;

    public function testBroadcastCorrectAttributes(): void
    {
        self::assertEquals([
            'name' => '::name::',
            'type' => PaletteType::YES,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new PaletteItemDeleted(
            new Palette(['name' => '::name::', 'type' => PaletteType::YES]),
            new History(['id' => 999]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.999';
    }
}
