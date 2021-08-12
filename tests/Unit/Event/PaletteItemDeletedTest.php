<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\History;
use App\Palette;
use App\PaletteType;
use PHPUnit\Framework\TestCase;
use App\Events\PaletteItemDeleted;

class PaletteItemDeletedTest extends TestCase
{
    use BroadcastingEventTest;

    /** @test */
    public function broadcastCorrectAttributes(): void
    {
        $this->assertEquals([
            'name' => '::name::',
            'type' => PaletteType::YES,
        ], $this->createEvent()->broadcastWith());
    }

    protected function createEvent()
    {
        return new PaletteItemDeleted(
            new Palette(['name' => '::name::', 'type' => PaletteType::YES]),
            new History(['id' => 999])
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.999';
    }
}
