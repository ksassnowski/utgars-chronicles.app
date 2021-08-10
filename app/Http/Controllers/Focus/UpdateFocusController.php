<?php declare(strict_types=1);

namespace App\Http\Controllers\Focus;

use App\Focus;
use App\History;
use App\Events\FocusUpdated;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\History\UpdateFocusRequest;

final class UpdateFocusController
{
    public function __invoke(UpdateFocusRequest $request, History $history, Focus $focus): RedirectResponse
    {
        $focus->update($request->validated());

        broadcast(new FocusUpdated($focus))->toOthers();

        return redirect()->back();
    }
}
