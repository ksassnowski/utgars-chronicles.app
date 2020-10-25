<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use App\Exceptions\OwnerCannotJoinOwnGameAsPlayer;

class AcceptInvitationController extends Controller
{
    public function __invoke(Request $request, History $history): RedirectResponse
    {
        if (!$history->public && $request->user()->isGuest()) {
            return redirect()->route('login');
        }

        try {
            $history->addPlayer($request->user());
        } catch (UserIsAlreadyPlayerInHistory $e) {
            return redirect()->route('home')->withErrors([
                'invitation' => __('You are already a player in this game'),
            ]);
        } catch (OwnerCannotJoinOwnGameAsPlayer $e) {
            return redirect()->route('home')->withErrors([
                'invitation' => __('You cannot accept an invitation to your own game'),
            ]);
        }

        return redirect()->route('home');
    }
}
