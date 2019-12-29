<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\UpdateHistoryRequest;

final class UpdateHistoryController
{
    public function __invoke(UpdateHistoryRequest $request, History $history): RedirectResponse
    {
        $history->update($request->validated());

        return back();
    }
}
