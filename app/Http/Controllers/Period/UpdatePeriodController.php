<?php

declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\UpdatePeriodRequest;
use App\Period;
use Illuminate\Http\RedirectResponse;

final class UpdatePeriodController
{
    public function __invoke(UpdatePeriodRequest $request, History $history, Period $period): RedirectResponse
    {
        $period->update($request->validated());

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
