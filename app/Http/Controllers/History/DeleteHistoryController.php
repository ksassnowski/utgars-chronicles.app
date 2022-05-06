<?php

declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeleteHistoryController
{
    public function __invoke(Request $request, History $history): RedirectResponse
    {
        $history->delete();

        return redirect()->route('home')
            ->with('success', __('History successfully deleted'));
    }
}
