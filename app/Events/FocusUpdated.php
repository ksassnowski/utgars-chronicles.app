<?php declare(strict_types=1);

namespace App\Events;

use App\Focus;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FocusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
