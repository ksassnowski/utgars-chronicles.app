<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\RedirectResponse;
use App\Notifications\FeedbackSubmitted;
use App\Http\Requests\SubmitFeedbackRequest;

final class FeedbackController
{
    public function __invoke(SubmitFeedbackRequest $request): RedirectResponse
    {
        /** @var User $admin */
        $admin = User::where('email', config('app.admin_email'))->first();

        $admin->notify(new FeedbackSubmitted($request->user(), $request->message()));

        return back()->with('success', 'Thank you for your feedback!');
    }
}
