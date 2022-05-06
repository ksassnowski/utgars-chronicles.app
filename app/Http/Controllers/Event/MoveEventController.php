<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\MoveEventRequest;
use Illuminate\Http\RedirectResponse;

final class MoveEventController
{
    public function __invoke(MoveEventRequest $request, History $history, Event $event): RedirectResponse
    {
        $event->move($request->position());

        broadcast(new BoardUpdated($event->period->history))->toOthers();

        return redirect()->back();
    }
}
