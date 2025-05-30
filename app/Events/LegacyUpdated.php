<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Events;

use App\Legacy;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class LegacyUpdated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Legacy $legacy,
    ) {
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->legacy->history_id);
    }

    /**
     * @return array<string, int|string>
     */
    public function broadcastWith(): array
    {
        return $this->legacy->only(['id', 'name']);
    }
}
