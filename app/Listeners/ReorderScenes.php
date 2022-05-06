<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\SceneDeleted;
use App\Scene;
use DB;

final class ReorderScenes
{
    public function handle(SceneDeleted $event): void
    {
        Scene::where('event_id', $event->event->id)
            ->where('position', '>', $event->position)
            ->update([
                'position' => DB::raw('position - 1'),
            ]);
    }
}
