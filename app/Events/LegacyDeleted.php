<?php declare(strict_types=1);

namespace App\Events;

use App\History;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LegacyDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $legacyId;
    public History $history;

    public function __construct(int $legacyId, History $history)
    {
        $this->legacyId = $legacyId;
        $this->history = $history;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->history->id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->legacyId,
        ];
    }
}
