<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\Events\BoardUpdated;
use App\Http\Requests\History\CreateEventRequest;

final class CreateEventController
{
    public function __invoke(CreateEventRequest $request, Period $period)
    {
        $period->events()->create($request->validated());

        broadcast(new BoardUpdated($period->history));

        return response()->json([], 201);
    }
}
