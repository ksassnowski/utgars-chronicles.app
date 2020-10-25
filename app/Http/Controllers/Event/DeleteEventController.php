<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\History;
use App\Events\BoardUpdated;
use Illuminate\Http\JsonResponse;

final class DeleteEventController
{
    public function __invoke(History $history, Event $event): JsonResponse
    {
        $event->delete();

        broadcast(new BoardUpdated($history->fresh()));

        return response()->json([], 204);
    }
}
