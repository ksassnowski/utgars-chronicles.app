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
use App\Http\Requests\History\UpdateEventRequest;
use Illuminate\Http\RedirectResponse;

final class UpdateEventController
{
    public function __invoke(UpdateEventRequest $request, History $history, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        broadcast(new BoardUpdated($history->refresh()))->toOthers();

        return redirect()->back();
    }
}
