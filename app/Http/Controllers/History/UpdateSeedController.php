<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\JsonResponse;
use App\Events\HistorySeedUpdated;
use App\Http\Requests\History\UpdateSeedRequest;

final class UpdateSeedController
{
    public function __invoke(UpdateSeedRequest $request, History $history): JsonResponse
    {
        $history->update($request->validated());

        broadcast(new HistorySeedUpdated($history));

        return response()->json();
    }
}
