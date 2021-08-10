<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\CreateEventRequest;

final class CreateEventController
{
    public function __invoke(CreateEventRequest $request, History $history, Period $period): RedirectResponse
    {
        $period->insertEvent($request->validated());

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
