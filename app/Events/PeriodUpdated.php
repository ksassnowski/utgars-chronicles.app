<?php declare(strict_types=1);

namespace App\Events;

use App\Period;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PeriodUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Period $period;

    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->period->history_id);
    }
}
