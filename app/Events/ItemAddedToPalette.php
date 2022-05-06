<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Events;

use App\Palette;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemAddedToPalette implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
