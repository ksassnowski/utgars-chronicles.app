<?php declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Scene;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class DeleteSceneController
{
    public function __invoke(Request $request, History $history, Scene $scene): JsonResponse
    {
        $scene->delete();

        broadcast(new BoardUpdated($history->fresh()));

        return response()->json([], 204);
    }
}
