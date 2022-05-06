<?php

declare(strict_types=1);

namespace App\Events;

use App\Focus;
use App\History;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FocusDefined implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
