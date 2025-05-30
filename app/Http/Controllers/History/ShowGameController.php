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
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ShowGameController
{
    public function __invoke(Request $request, History $game): Response
    {
        $game->load(['owner', 'players']);

        return Inertia::render('Game/Show', [
            'game' => $game,
        ]);
    }
}
