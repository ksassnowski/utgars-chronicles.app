<?php

declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Events\BoardUpdated;
use App\History;
use App\Period;
use Illuminate\Http\RedirectResponse;

final class DeletePeriodController
{
    public function __invoke(History $history, Period $period): RedirectResponse
    {
        $period->delete();

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
