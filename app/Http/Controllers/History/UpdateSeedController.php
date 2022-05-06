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

use App\Events\HistorySeedUpdated;
use App\History;
use App\Http\Requests\History\UpdateSeedRequest;
use Illuminate\Http\RedirectResponse;

final class UpdateSeedController
{
    public function __invoke(UpdateSeedRequest $request, History $history): RedirectResponse
    {
        $history->update($request->validated());

        broadcast(new HistorySeedUpdated($history))->toOthers();

        return redirect()->back();
    }
}
