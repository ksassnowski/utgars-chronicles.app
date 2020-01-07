<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\Request;
use App\Exceptions\UserIsNotAPlayer;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LeaveGameController extends Controller
{
    public function __invoke(Request $request, History $game): RedirectResponse
    {
        try {
            $game->removePlayer($request->user());
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
