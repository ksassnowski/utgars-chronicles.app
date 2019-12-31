<?php declare(strict_types=1);

namespace App\Events;

use App\History;
use App\Palette;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ItemAddedToPalette implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Palette $item;
    public History $history;

    public function __construct(Palette $item, History $history)
    {
        $this->item = $item;
        $this->history = $history;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('history.' . $this->history->id);
    }

    public function broadcastWith(): array
    {
        return [
            'history' => $this->history->id,
            'item' => [
                'id' => $this->item->id,
                'name' => $this->item->name,
                'type' => $this->item->type,
            ],
        ];
    }
}
