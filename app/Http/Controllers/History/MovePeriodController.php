<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\Period;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\MovePeriodRequest;

final class MovePeriodController
{
    public function __invoke(MovePeriodRequest $request, History $history, Period $period): JsonResponse
    {
        $period->move($request->position());

        broadcast(new BoardUpdated($history))->toOthers();

        return response()->json([], 200);
    }
}
