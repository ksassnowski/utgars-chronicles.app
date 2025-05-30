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

namespace App\Http\Controllers\Period;

use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\UpdatePeriodRequest;
use App\Period;
use Illuminate\Http\RedirectResponse;

final class UpdatePeriodController
{
    public function __invoke(UpdatePeriodRequest $request, History $history, Period $period): RedirectResponse
    {
        $period->update($request->validated());

        broadcast(new BoardUpdated($history->refresh()))->toOthers();

        return redirect()->back();
    }
}
