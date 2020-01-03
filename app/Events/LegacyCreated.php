<?php declare(strict_types=1);

namespace App\Events;

use App\Legacy;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class LegacyCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Legacy $legacy;

    public function __construct(Legacy $legacy)
    {
        $this->legacy = $legacy;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->legacy->history_id);
    }

    public function broadcastWith(): array
    {
        return $this->legacy->only(['id', 'name']);
    }
}
