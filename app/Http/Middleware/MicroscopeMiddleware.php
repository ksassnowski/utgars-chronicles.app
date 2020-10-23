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

        $user = $request->user();

        if (!$this->canAccessHistory($user, $history)) {
            abort(403);
        }

        return $next($request);
    }

    private function canAccessHistory(?User $user, History $history): bool
    {
        if ($user === null) {
            return $this->canJoinPublicHistory($history);
        }

        if ($history->owner_id === $user->id) {
            return true;
        }

        if ($history->isPlayer($user)) {
            return true;
        }

        return false;
    }

    private function canJoinPublicHistory(History $history): bool
    {
        if (!$history->public) {
            return false;
        }

        $invitedHistoryIds = session()->get('invited-histories');

        return in_array($history->id, $invitedHistoryIds);
    }
}
