<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\User;
use App\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\UserIsAlreadyPlayerInHistory;

class AcceptInvitationController extends Controller
{
    public function __invoke(Request $request, History $history): RedirectResponse
    {
        $user = User::where(['email' => $request->get('email')])->first();

        try {
            $history->addPlayer($user);
        } catch (UserIsAlreadyPlayerInHistory $e) {
            return redirect()->route('home')->withErrors([
                'invitation' => __('You are already a player in this game'),
            ]);
        }

        return redirect()->route('home');
    }
}
