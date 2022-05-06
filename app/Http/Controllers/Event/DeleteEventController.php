<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use Illuminate\Http\RedirectResponse;

final class DeleteEventController
{
    public function __invoke(History $history, Event $event): RedirectResponse
    {
        $event->delete();

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
