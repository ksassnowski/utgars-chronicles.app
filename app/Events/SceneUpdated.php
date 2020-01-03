<?php declare(strict_types=1);

namespace App\Events;

use App\Scene;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SceneUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Scene $scene;

    public function __construct(Scene $scene)
    {
        $this->scene = $scene;
    }

    public function broadcastOn(): Channel
    {
        return new PresenceChannel('history.' . $this->scene->event->period->history_id);
    }

    public function broadcastWith(): array
    {
        return [
            'scene' => $this->scene,
            'event' => $this->scene->event->id,
            'period' => $this->scene->event->period->id,
        ];
    }
}
