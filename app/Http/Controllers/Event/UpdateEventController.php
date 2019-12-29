<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\EventUpdated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\History\UpdateEventRequest;

final class UpdateEventController
{
    public function __invoke(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $event->update($request->validated());

        broadcast(new EventUpdated($event))->toOthers();

        return response()->json();
    }
}
