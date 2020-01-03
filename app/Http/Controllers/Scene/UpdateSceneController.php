<?php declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Scene;
use App\Events\SceneUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\UpdateSceneRequest;

final class UpdateSceneController
{
    public function __invoke(UpdateSceneRequest $request, Scene $scene): JsonResponse
    {
        $scene->update($request->validated());

        broadcast(new SceneUpdated($scene));

        return response()->json([], 200);
    }
}
