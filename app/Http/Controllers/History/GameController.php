<?php

declare(strict_types=1);

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
