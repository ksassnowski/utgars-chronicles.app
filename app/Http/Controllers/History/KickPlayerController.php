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

namespace App\Http\Controllers\History;

use App\History;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class KickPlayerController
{
    public function __invoke(Request $request, History $history, User $player): RedirectResponse
    {
        $history->removePlayer($player);

        return redirect()
            ->route('history.show', $history)
            ->with('success', __('Successfully removed ' . $player->name . ' from the game.'));
    }
}
