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

use App\Exceptions\OwnerCannotJoinOwnGameAsPlayer;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use App\History;
use App\Http\Controllers\Controller;
use App\MicroscopePlayer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

final class AcceptInvitationController extends Controller
{
    public function __invoke(Request $request, History $history): RedirectResponse
    {
        /** @var MicroscopePlayer $user */
        $user = $request->user();

        if (!$history->public && $user->isGuest()) {
            redirect()->setIntendedUrl($request->fullUrl());

            return redirect()->route('login');
        }

        if ($user->isGuest()) {
            return redirect()->to(
                URL::signedRoute('invitation.accept.show-form', ['history' => $history]),
            );
        }

        try {
            /** @phpstan-ignore-next-line */
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

        return redirect()->route('user.games.show', $history);
    }
}
