<?php declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use App\Http\Requests\MoveEventRequest;

final class MoveEventController
{
    public function __invoke(MoveEventRequest $request, Event $event)
    {
        $event->move($request->position());

        broadcast(new BoardUpdated($event->period->history))->toOthers();

        return response()->json([], 200);
    }
}
