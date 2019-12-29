<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\Period;
use App\History;
use App\Events\PeriodCreated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\CreatePeriodRequest;

final class CreatePeriodController
{
    public function __invoke(CreatePeriodRequest $request, History $history): JsonResponse
    {
        /** @var Period $period */
        $period = $history->periods()->create($request->validated());

        broadcast(new PeriodCreated($period))->toOthers();

        return response()->json([], 201);
    }
}
