<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Period;
use App\Events\PeriodUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\UpdatePeriodRequest;

final class UpdatePeriodController
{
    public function __invoke(UpdatePeriodRequest $request, Period $period): JsonResponse
    {
        $period->update($request->validated());

        broadcast(new PeriodUpdated($period));

        return response()->json();
    }
}
