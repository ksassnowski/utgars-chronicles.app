<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\History;
use App\Palette;
use App\PaletteType;
use PHPUnit\Framework\TestCase;
use App\Events\ItemAddedToPalette;

class ItemAddedToPaletteTest extends TestCase
{
    use BroadcastingEventTest;

    protected function createEvent()
    {
        return new ItemAddedToPalette(
            new Palette([
                'id' => 999,
                'name' => '::entry-name::',
                'type' => PaletteType::YES,
                'history_id' => 123,
            ]),
            new History([
                'id' => 123,
            ])
        );
    }

    protected function getChannelName(ItemAddedToPalette $event): string
    {
        return 'history.' . $event->history->id;
    }

    /** @test */
    public function broadcastCorrectAttributes(): void
    {
        $this->assertEquals([
            'history' => 123,
            'item' => [
                'id' => 999,
                'name' => '::entry-name::',
                'type' => PaletteType::YES,
            ],
        ], $this->createEvent()->broadcastWith());
    }
}
