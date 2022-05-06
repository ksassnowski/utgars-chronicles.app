<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\UpdateEventRequest;
use Illuminate\Http\RedirectResponse;

final class UpdateEventController
{
    public function __invoke(UpdateEventRequest $request, History $history, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
