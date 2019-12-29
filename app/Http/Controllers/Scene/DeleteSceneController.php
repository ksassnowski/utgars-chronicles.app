<?php declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Scene;
use App\Events\SceneDeleted;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class DeleteSceneController
{
    public function __invoke(Request $request, Scene $scene): JsonResponse
    {
        $scene->delete();

        broadcast(new SceneDeleted($scene->event, $scene->id))->toOthers();

        return response()->json([], 204);
    }
}
