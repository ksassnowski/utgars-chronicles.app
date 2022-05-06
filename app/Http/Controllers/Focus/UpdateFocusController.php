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

namespace App\Http\Controllers\Focus;

use App\Events\FocusUpdated;
use App\Focus;
use App\History;
use App\Http\Requests\History\UpdateFocusRequest;
use Illuminate\Http\RedirectResponse;

final class UpdateFocusController
{
    public function __invoke(UpdateFocusRequest $request, History $history, Focus $focus): RedirectResponse
    {
        $focus->update($request->validated());

        broadcast(new FocusUpdated($focus))->toOthers();

        return redirect()->back();
    }
}
