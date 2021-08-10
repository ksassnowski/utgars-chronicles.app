<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\UpdatePeriodRequest;

final class UpdatePeriodController
{
    public function __invoke(UpdatePeriodRequest $request, History $history, Period $period): RedirectResponse
    {
        $period->update($request->validated());

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
