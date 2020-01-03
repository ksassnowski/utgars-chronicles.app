<?php declare(strict_types=1);

namespace App\Events;

use App\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EventCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->event->period->history_id);
    }

    public function broadcastWith(): array
    {
        return [
            'event' => $this->event,
            'period' => $this->event->period->id,
        ];
    }
}
