<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\History\ShowInvitationFormRequest;
use App\Http\Requests\History\AcceptGuestInvitationRequest;

class GuestInvitationController extends Controller
{
    public function showForm(ShowInvitationFormRequest $request, History $history): Response
    {
        return Inertia::render('Game/InvitationForm', [
            'acceptUrl' => URL::signedRoute('invitation.accept.guest', ['history' => $history], now()->addMinutes(5)),
            'inviteeName' => $history->owner->name,
        ]);
    }

    public function accept(AcceptGuestInvitationRequest $request, History $history)
    {
        $request->user()->joinGame($history, $request->name());

        return redirect()->route('history.play', $history);
    }
}
