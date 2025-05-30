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

namespace App\Http\Controllers\History;

use App\Events\FocusDefined;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Requests\History\DefineFocusRequest;
use Illuminate\Http\RedirectResponse;

class DefineFocusController extends Controller
{
    public function __invoke(DefineFocusRequest $request, History $history): RedirectResponse
    {
        $focus = $history->defineFocus($request->name());

        broadcast(new FocusDefined($history, $focus))->toOthers();

        return redirect()->back();
    }
}
