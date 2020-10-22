<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\CreateEventRequest;

final class CreateEventController
{
    public function __invoke(CreateEventRequest $request, History $history, Period $period): JsonResponse
    {
        $period->insertEvent($request->validated());

        broadcast(new BoardUpdated($period->history));

        return response()->json([], 201);
    }
}
