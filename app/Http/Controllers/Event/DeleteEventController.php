<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\EventDeleted;
use Illuminate\Http\JsonResponse;

final class DeleteEventController
{
    public function __invoke(Event $event): JsonResponse
    {
        $event->delete();

        broadcast(new EventDeleted($event->period, $event->id, $event->position));

        return response()->json([], 204);
    }
}
