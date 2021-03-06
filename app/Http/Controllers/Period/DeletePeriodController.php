<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;

final class DeletePeriodController
{
    public function __invoke(History $history, Period $period): JsonResponse
    {
        $period->delete();

        broadcast(new BoardUpdated($history->fresh()));

        return response()->json([], 204);
    }
}
