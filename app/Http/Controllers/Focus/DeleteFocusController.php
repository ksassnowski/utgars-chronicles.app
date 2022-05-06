<?php

declare(strict_types=1);

namespace App\Http\Controllers\Focus;

use App\Events\FocusDeleted;
use App\Focus;
use App\History;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeleteFocusController
{
    public function __invoke(Request $request, History $history, Focus $focus): RedirectResponse
    {
        $focus->delete();

        broadcast(new FocusDeleted($history, $focus->id))->toOthers();

        return redirect()->back();
    }
}
