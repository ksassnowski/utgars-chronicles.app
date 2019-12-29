<?php declare(strict_types=1);

namespace App\Http\Controllers\Period;

use App\Event;
use App\Period;
use App\History;
use App\Events\EventCreated;
use App\Http\Requests\History\CreateEventRequest;

final class CreateEventController
{
    public function __invoke(CreateEventRequest $request, History $history, Period $period)
    {
        /** @var Event $event */
        $event = $period->events()->create($request->validated());

        broadcast(new EventCreated($event))->toOthers();

        return response()->json([], 201);
    }
}
