<?php declare(strict_types=1);

namespace App\Listeners;

use DB;
use App\Period;
use App\Events\PeriodDeleted;

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
