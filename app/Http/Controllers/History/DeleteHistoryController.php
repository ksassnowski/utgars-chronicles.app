<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

final class DeleteHistoryController
{
    public function __invoke(Request $request, History $history): RedirectResponse
    {
        $history->delete();

        return redirect()->route('home');
    }
}
