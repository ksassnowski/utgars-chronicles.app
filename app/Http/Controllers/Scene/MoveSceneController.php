<?php declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Scene;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\MoveSceneRequest;

final class MoveSceneController
{
    public function __invoke(MoveSceneRequest $request, History $history, Scene $scene): JsonResponse
    {
        $scene->move($request->position());

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return response()->json();
    }
}
