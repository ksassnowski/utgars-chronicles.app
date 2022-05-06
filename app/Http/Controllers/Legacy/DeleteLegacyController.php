<?php

declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\Events\LegacyDeleted;
use App\History;
use App\Legacy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeleteLegacyController
{
    public function __invoke(Request $request, History $history, Legacy $legacy): RedirectResponse
    {
        $legacy->delete();

        broadcast(new LegacyDeleted($legacy->id, $legacy->history))->toOthers();

        return redirect()->back();
    }
}
