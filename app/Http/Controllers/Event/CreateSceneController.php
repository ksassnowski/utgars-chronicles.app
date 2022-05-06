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
use App\Http\Requests\History\CreateSceneRequest;
use Illuminate\Http\RedirectResponse;

final class CreateSceneController
{
    public function __invoke(CreateSceneRequest $request, History $history, Event $event): RedirectResponse
    {
        $event->scenes()->create(\array_merge(
            $request->validated(),
            ['history_id' => $history->id],
        ));

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
