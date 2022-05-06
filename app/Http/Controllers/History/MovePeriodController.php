<?php

declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\MovePeriodRequest;
use App\Period;
use Illuminate\Http\RedirectResponse;

final class MovePeriodController
{
    public function __invoke(MovePeriodRequest $request, History $history, Period $period): RedirectResponse
    {
        $period->move($request->position());

        broadcast(new BoardUpdated($history))->toOthers();

        return redirect()->back();
    }
}
