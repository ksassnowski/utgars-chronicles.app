<?php declare(strict_types=1);

namespace App\Events;

use App\Focus;
use App\History;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class FocusDefined implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public History $history;
    public Focus $focus;

    public function __construct(History $history, Focus $focus)
    {
        $this->history = $history;
        $this->focus = $focus;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->history->id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->focus->id,
            'name' => $this->focus->name,
        ];
    }
}
