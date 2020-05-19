<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\JsonResponse;

final class BoardController
{
    public function __invoke(History $history): JsonResponse
    {
        $history->load('periods.events.scenes');

        return response()->json($history, 200);
    }
}
