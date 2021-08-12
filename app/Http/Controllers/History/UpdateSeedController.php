<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use App\Events\HistorySeedUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\UpdateSeedRequest;

final class UpdateSeedController
{
    public function __invoke(UpdateSeedRequest $request, History $history): RedirectResponse
    {
        $history->update($request->validated());

        broadcast(new HistorySeedUpdated($history))->toOthers();

        return redirect()->back();
    }
}
