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
use App\Http\Controllers\Controller;
use App\Http\Requests\History\AcceptGuestInvitationRequest;
use App\Http\Requests\History\ShowInvitationFormRequest;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

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
