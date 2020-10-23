<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\History;
use Illuminate\Http\Request;

class MicroscopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var History $history */
        if (($history = $request->route('history')) === null) {
            return $next($request);
        }

        if (($user = $request->user()) === null) {
            abort(401);
        }

        if (!$this->canAccessHistory($user, $history)) {
            abort(403);
        }

        return $next($request);
    }

    private function canAccessHistory(User $user, History $history): bool
    {
        if ($history->owner_id === $user->id) {
            return true;
        }

        if ($history->players()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }
}
