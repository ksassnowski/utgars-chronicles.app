<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

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
