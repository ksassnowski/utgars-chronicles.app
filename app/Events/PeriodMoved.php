<?php declare(strict_types=1);

namespace App\Events;

use App\Period;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PeriodMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Period $period;

    public int $newPosition;

    public function __construct(Period $period, int $newPosition)
    {
        $this->period = $period;
        $this->newPosition = $newPosition;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('history.' . $this->period->history_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->period->id,
            'position' => $this->newPosition,
        ];
    }
}
