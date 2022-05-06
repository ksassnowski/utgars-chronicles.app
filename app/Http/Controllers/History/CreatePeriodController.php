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

use App\Events\BoardUpdated;
use App\History;
use App\Http\Requests\History\CreatePeriodRequest;
use Illuminate\Http\RedirectResponse;

final class CreatePeriodController
{
    public function __invoke(CreatePeriodRequest $request, History $history): RedirectResponse
    {
        $history->insertPeriod($request->validated());

        broadcast(new BoardUpdated($history->refresh()))->toOthers();

        return redirect()->back();
    }
}
