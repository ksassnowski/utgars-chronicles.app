<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\History;
use App\MicroscopePlayer;
use Closure;
use Illuminate\Http\Request;

class MicroscopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var History $history */
        if (null === ($history = $request->route('history'))) {
            return $next($request);
        }

        /** @var MicroscopePlayer $player */
        $player = $request->user('microscope');

        if (!$history->public && $player->isGuest()) {
            abort(403);
        }

        if (!$player->isPlayer($history)) {
            abort(403);
        }

        return $next($request);
    }
}
