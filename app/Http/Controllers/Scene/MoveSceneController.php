<?php declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Scene;
use App\Events\SceneMoved;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\MoveSceneRequest;

final class MoveSceneController
{
    public function __invoke(MoveSceneRequest $request, Scene $scene): JsonResponse
    {
        $scene->move($request->position());

        broadcast(new SceneMoved($scene, $request->position()))->toOthers();

        return response()->json();
    }
}
