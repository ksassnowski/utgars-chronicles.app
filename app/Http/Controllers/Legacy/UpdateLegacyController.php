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

namespace App\Http\Controllers\Legacy;

use App\Events\LegacyUpdated;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Requests\Legacy\UpdateLegacyRequest;
use App\Legacy;
use Illuminate\Http\RedirectResponse;

class UpdateLegacyController extends Controller
{
    public function __invoke(UpdateLegacyRequest $request, History $history, Legacy $legacy): RedirectResponse
    {
        $legacy->update($request->validated());

        broadcast(new LegacyUpdated($legacy))->toOthers();

        return redirect()->back();
    }
}
