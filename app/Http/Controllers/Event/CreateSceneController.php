<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Scene;
use App\Events\SceneCreated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\CreateSceneRequest;

final class CreateSceneController
{
    public function __invoke(CreateSceneRequest $request, Event $event): JsonResponse
    {
        /** @var Scene $scene */
        $scene = $event->scenes()->create($request->validated());

        broadcast(new SceneCreated($scene))->toOthers();

        return response()->json([], 201);
    }
}
