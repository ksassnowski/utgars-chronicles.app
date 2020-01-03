<?php declare(strict_types=1);

namespace App\Events;

use App\History;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PaletteItemDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $itemId;
    public History $history;

    public function __construct(int $itemId, History $history)
    {
        $this->itemId = $itemId;
        $this->history = $history;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->history->id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->itemId,
        ];
    }
}
