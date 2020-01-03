<?php declare(strict_types=1);

namespace App\Events;

use App\History;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class FocusDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public History $history;
    public int $focusId;

    public function __construct(History $history, int $focusId)
    {
        $this->history = $history;
        $this->focusId = $focusId;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->history->id);
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->focusId];
    }
}
