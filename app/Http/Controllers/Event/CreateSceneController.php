<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\CreateSceneRequest;

final class CreateSceneController
{
    public function __invoke(CreateSceneRequest $request, Event $event): JsonResponse
    {
        $event->scenes()->create($request->validated());

        broadcast(new BoardUpdated($event->period->history));

        return response()->json([], 201);
    }
}
