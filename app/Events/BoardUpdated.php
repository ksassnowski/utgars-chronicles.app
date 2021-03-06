<?php declare(strict_types=1);

namespace App\Events;

use App\History;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class BoardUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private History $history;

    public function __construct(History $history)
    {
        $this->history = $history;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('history.' . $this->history->id);
    }
}
