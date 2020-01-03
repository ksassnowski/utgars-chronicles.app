<?php declare(strict_types=1);

namespace App\Events;

use App\Palette;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PaletteItemUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Palette $item;

    public function __construct(Palette $item)
    {
        $this->item = $item;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('history.' . $this->item->history_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->item->id,
            'name' => $this->item->name,
            'type' => $this->item->type,
        ];
    }
}
