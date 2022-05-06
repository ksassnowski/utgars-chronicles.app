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
