<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\CreatePeriodRequest;

final class CreatePeriodController
{
    public function __invoke(CreatePeriodRequest $request, History $history): JsonResponse
    {
        $history->periods()->create($request->validated());

        broadcast(new BoardUpdated($history));

        return response()->json([], 201);
    }
}
