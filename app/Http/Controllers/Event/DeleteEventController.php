<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

final class DeleteEventController
{
    public function __invoke(History $history, Event $event): RedirectResponse
    {
        DB::transaction(static function () use ($history, $event): void {
            $event->delete();

            if ($history->events()->where('echo_group', $event->echo_group)->count() === 1) {
                $history->events()
                    ->where('echo_group', $event->echo_group)
                    ->update(['echo_group' => null, 'echo_group_position' => 1]);
            }
        });

        broadcast(new BoardUpdated($history->refresh()))->toOthers();

        return redirect()->back();
    }
}
