<?php

declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\Http\Requests\History\CreateHistoryRequest;
use App\User;
use Illuminate\Http\RedirectResponse;

final class StoreHistoryController
{
    public function __invoke(CreateHistoryRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $history = $user->histories()->create($request->validated());

        return redirect()
            ->route('history.show', $history)
            ->with('success', __('History created'));
    }
}
