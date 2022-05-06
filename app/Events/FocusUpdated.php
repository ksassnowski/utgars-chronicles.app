<?php

declare(strict_types=1);

namespace App\Events;

use App\Focus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FocusUpdated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Focus $focus;

    public function __construct(Focus $focus)
    {
        $this->focus = $focus;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('history.' . $this->focus->history_id);
    }
}
