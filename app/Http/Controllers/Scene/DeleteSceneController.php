<?php

declare(strict_types=1);

namespace App\Http\Controllers\Scene;

use App\Events\BoardUpdated;
use App\History;
use App\Scene;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeleteSceneController
{
    public function __invoke(Request $request, History $history, Scene $scene): RedirectResponse
    {
        $scene->delete();

        broadcast(new BoardUpdated($history->fresh()))->toOthers();

        return redirect()->back();
    }
}
