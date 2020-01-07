<?php declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\History;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

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
