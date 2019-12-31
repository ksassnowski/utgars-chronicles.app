<?php declare(strict_types=1);

namespace App\Policies;

use App\User;
use App\Legacy;

final class LegacyPolicy
{
    public function updateLegacy(User $user, Legacy $legacy): bool
    {
        return $this->isAuthorized($user, $legacy);
    }

    public function deleteLegacy(User $user, Legacy $legacy): bool
    {
        return $this->isAuthorized($user, $legacy);
    }

    private function isAuthorized(User $user, Legacy $legacy): bool
    {
        $history = $legacy->history;

        return $history->owner_id === $user->id
            || $history->players()->where('user_id', $user->id)->exists();
    }
}
