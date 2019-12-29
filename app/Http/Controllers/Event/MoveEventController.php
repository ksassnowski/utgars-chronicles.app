<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\EventMoved;
use App\Http\Requests\MoveEventRequest;

final class MoveEventController
{
    public function __invoke(MoveEventRequest $request, Event $event)
    {
        $event->move($request->position());

        broadcast(new EventMoved($event, $request->position()))->toOthers();

        return response()->json([], 200);
    }
}
