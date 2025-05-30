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

namespace App\Http\Controllers;

use App\Exceptions\AlreadyRequestedToJoinException;
use App\Exceptions\GameAlreadyStartedException;
use App\Exceptions\LfgAlreadyFullException;
use App\Exceptions\RequestAlreadyAnsweredException;
use App\Lfg;
use App\LfgRequest;
use App\Notifications\LfgRequestWasAccepted;
use App\Notifications\LfgRequestWasRejected;
use App\Notifications\NewLfgRequest;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LfgRequestController extends Controller
{
    public function index(): void
    {
    }

    public function store(Request $request, Lfg $lfg): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            /** @var string $message */
            $message = $request->post('message');

            $lfgRequest = $lfg->requestToJoin($user, $message);
        } catch (AlreadyRequestedToJoinException|GameAlreadyStartedException|LfgAlreadyFullException $e) {
            return $this->unsuccessfulRequestResponse($e);
        }

        $lfg->owner->notify(new NewLfgRequest($lfgRequest));

        return redirect()->route('lfg.requests.index');
    }

    public function accept(LfgRequest $request): RedirectResponse
    {
        try {
            $request->accept();
        } catch (RequestAlreadyAnsweredException) {
            return redirect()->back()->withErrors([
                'request' => __('Can only accept pending requests'),
            ]);
        }

        if ($request->lfg->isFull()) {
            $request->lfg->clearPendingRequests();
        }

        $request->user->notify(new LfgRequestWasAccepted($request));

        return redirect()->back();
    }

    public function reject(LfgRequest $request): RedirectResponse
    {
        try {
            $request->reject();
        } catch (RequestAlreadyAnsweredException) {
            return redirect()->back()->withErrors([
                'request' => __('Can only reject pending requests'),
            ]);
        }

        $request->user->notify(new LfgRequestWasRejected($request));

        return redirect()->back();
    }

    private function unsuccessfulRequestResponse(\Throwable $exception): RedirectResponse
    {
        $message = match ($exception::class) {
            AlreadyRequestedToJoinException::class => 'You already have a pending request for this game',
            LfgAlreadyFullException::class => 'Game is already full',
            GameAlreadyStartedException::class => 'Game has already happened',
            default => 'An unknown error occurred',
        };

        return redirect()->back()->withErrors(['lfg' => __($message)]);
    }
}
