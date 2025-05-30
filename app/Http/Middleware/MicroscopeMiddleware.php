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

namespace App\Http\Middleware;

use App\History;
use App\MicroscopePlayer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class MicroscopeMiddleware
{
    /**
     * @param \Closure(Request): Response $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        /** @var null|History $history */
        $history = $request->route('history');

        if (null === $history) {
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
