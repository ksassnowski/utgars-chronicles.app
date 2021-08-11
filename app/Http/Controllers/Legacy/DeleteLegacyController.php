<?php declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\Legacy;
use App\History;
use Illuminate\Http\Request;
use App\Events\LegacyDeleted;
use Illuminate\Http\RedirectResponse;

final class DeleteLegacyController
{
    public function __invoke(Request $request, History $history, Legacy $legacy): RedirectResponse
    {
        $legacy->delete();

        broadcast(new LegacyDeleted($legacy->id, $legacy->history))->toOthers();

        return redirect()->back();
    }
}
