<?php declare(strict_types=1);

namespace App\Events;

use App\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EventMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Event $event;
    public int $newPosition;

    public function __construct(Event $event, int $newPosition)
    {
        $this->event = $event;
        $this->newPosition = $newPosition;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->event->period->history_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->event->id,
            'period' => $this->event->period->id,
            'position' => $this->newPosition,
        ];
    }
}
