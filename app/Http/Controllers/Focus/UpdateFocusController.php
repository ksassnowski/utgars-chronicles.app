<?php declare(strict_types=1);

namespace App\Http\Controllers\Focus;

use App\Focus;
use App\History;
use App\Events\FocusUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\UpdateFocusRequest;

final class UpdateFocusController
{
    public function __invoke(UpdateFocusRequest $request, History $history, Focus $focus): JsonResponse
    {
        $focus->update($request->validated());

        broadcast(new FocusUpdated($focus))->toOthers();

        return response()->json();
    }
}
