<?php declare(strict_types=1);

namespace App\Events;

use App\Period;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;

    public Period $period;

    public function __construct(Period $period, int $id)
    {
        $this->id = $id;
        $this->period = $period;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->period->history_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'period' => $this->period->id,
        ];
    }
}
