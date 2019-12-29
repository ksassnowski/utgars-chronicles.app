<?php declare(strict_types=1);

namespace App\Policies;

use App\User;
use App\Focus;

final class FocusPolicy
{
    public function editFocus(User $user, Focus $focus): bool
    {
        return $this->isAuthorized($user, $focus);
    }

    public function deleteFocus(User $user, Focus $focus): bool
    {
        return $this->isAuthorized($user, $focus);
    }

    private function isAuthorized(User $user, Focus $focus): bool
    {
        $history = $focus->history;

        return $history->owner_id === $user->id || $history->isPlayer($user);
    }
}
