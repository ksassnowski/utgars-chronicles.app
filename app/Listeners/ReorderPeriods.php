<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PeriodDeleted;
use App\Period;
use DB;

final class ReorderPeriods
{
    public function handle(PeriodDeleted $event): void
    {
        Period::where('history_id', $event->history->id)
            ->where('position', '>', $event->position)
            ->update([
                'position' => DB::raw('position - 1'),
            ]);
    }
}
