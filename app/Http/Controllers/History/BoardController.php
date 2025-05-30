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
use Illuminate\Http\JsonResponse;

final class BoardController
{
    public function __invoke(History $history): JsonResponse
    {
        $history->load('periods.events.scenes');

        return response()->json($history, 200);
    }
}
