<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Controllers\History;

use App\History;
use Inertia\Inertia;
use Inertia\Response;

final class GameController
{
    public function __invoke(History $history): Response
    {
        return Inertia::render('Game', [
            'history' => static fn () => $history->load(['periods.events.scenes']),
            'foci' => static fn () => $history->foci,
            'palettes' => static fn () => $history->palettes,
            'legacies' => static fn () => $history->legacies,
        ]);
    }
}
