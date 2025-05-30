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
