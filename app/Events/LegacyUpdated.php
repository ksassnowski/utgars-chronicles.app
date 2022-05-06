<?php

declare(strict_types=1);

namespace App\Events;

use App\Legacy;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LegacyUpdated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
