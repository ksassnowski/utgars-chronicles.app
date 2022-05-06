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

namespace App\Http\Controllers;

use App\Http\Requests\SubmitFeedbackRequest;
use App\Notifications\FeedbackSubmitted;
use App\User;
use Illuminate\Http\RedirectResponse;

final class FeedbackController
{
    public function __invoke(SubmitFeedbackRequest $request): RedirectResponse
    {
        /** @var User $admin */
        $admin = User::where('email', config('app.admin_email'))->first();

        /** @var User $user */
        $user = $request->user();

        $admin->notify(new FeedbackSubmitted($user, $request->message()));

        return back()->with('success', 'Thank you for your feedback!');
    }
}
