<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Focus;
use App\History;
use App\Events\FocusDefined;
use PHPUnit\Framework\TestCase;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FocusDefinedTest extends TestCase
{
    /** @test */
    public function broadCastWithCorrectData(): void
    {
        $history = new History();
        $focus = new Focus(['name' => '::focus-name::']);
        $focus->id = 999;
        $history->setRelation('focus', collect($focus));

        $event = new FocusDefined($history, $focus);

        $this->assertEquals([
            'id' => 999,
            'name' => '::focus-name::',
        ], $event->broadcastWith());
    }

    /** @test */
    public function broadcastOnCorrectChannel(): void
    {
        $history = new History(['id' => 1]);

        $event = new FocusDefined($history, new Focus());

        $this->assertEquals(new PresenceChannel('history.1'), $event->broadcastOn());
    }

    /** @test */
    public function eventShouldBroadcast(): void
    {
        $event = new FocusDefined(new History(), new Focus());

        $this->assertInstanceOf(ShouldBroadcast::class, $event);
    }
}
