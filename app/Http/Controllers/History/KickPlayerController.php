<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\User;
use App\History;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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
