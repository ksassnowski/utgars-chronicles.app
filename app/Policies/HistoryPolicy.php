<?php declare(strict_types=1);

namespace App\Policies;

use App\User;
use App\History;

class HistoryPolicy
{
    public function deleteHistory(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    public function showHistory(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    public function updateHistory(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    public function modifyGame(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history) || $this->isPlayer($user, $history);
    }

    public function kickPlayer(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    private function ownsHistory(User $user, History $history): bool
    {
        return $history->owner->is($user);
    }

    private function isPlayer(User $user, History $history): bool
    {
        return $history->players()->where('user_id', $user->id)->exists();
    }
}
