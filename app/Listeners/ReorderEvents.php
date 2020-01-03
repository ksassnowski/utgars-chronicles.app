<?php declare(strict_types=1);

namespace App\Listeners;

use App\Event;
use App\Events\EventDeleted;

class ReorderEvents
{
    public function handle(EventDeleted $event)
    {
        Event::where('period_id', $event->period->id)
            ->where('position', '>', $event->position)
            ->update([
                'position' => \DB::raw('position - 1'),
            ]);
    }
}
