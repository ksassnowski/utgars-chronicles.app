<?php

declare(strict_types=1);

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
