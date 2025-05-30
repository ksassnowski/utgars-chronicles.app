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

namespace App\Http\Controllers\History;

use App\Exceptions\UserIsNotAPlayer;
use App\History;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeaveGameController extends Controller
{
    public function __invoke(Request $request, History $game): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $game->removePlayer($user);
        } catch (UserIsNotAPlayer $exception) {
            return redirect()
                ->route('home')
                ->with('error', __('You are not a player of this game.'));
        }

        return redirect()
            ->route('home')
            ->with('success', __('You are no longer a player of "' . $game->name . '"'));
    }
}
