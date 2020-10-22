<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\UpdatePeriodRequest;

final class UpdatePeriodController
{
    public function __invoke(UpdatePeriodRequest $request, History $history, Period $period): JsonResponse
    {
        $period->update($request->validated());

        broadcast(new BoardUpdated($period->history));

        return response()->json();
    }
}
