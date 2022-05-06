<?php

declare(strict_types=1);

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

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
