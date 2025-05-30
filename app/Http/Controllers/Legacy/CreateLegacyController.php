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

use App\Events\LegacyCreated;
use App\History;
use App\Http\Controllers\Controller;
use App\Http\Requests\Legacy\CreateLegacyRequest;
use Illuminate\Http\RedirectResponse;

class CreateLegacyController extends Controller
{
    public function __invoke(CreateLegacyRequest $request, History $history): RedirectResponse
    {
        $legacy = $history->addLegacy($request->name());

        broadcast(new LegacyCreated($legacy))->toOthers();

        return redirect()->back();
    }
}
