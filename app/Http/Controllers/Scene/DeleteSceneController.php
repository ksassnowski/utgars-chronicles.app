<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

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

        broadcast(new BoardUpdated($history->refresh()))->toOthers();

        return redirect()->back();
    }
}
