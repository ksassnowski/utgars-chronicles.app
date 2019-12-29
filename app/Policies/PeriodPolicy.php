<?php declare(strict_types=1);

namespace App\Policies;

use App\User;
use App\Period;

final class PeriodPolicy
{
    public function createEvent(User $user, Period $period): bool
    {
        return $this->isAuthorized($user, $period);
    }

    public function updatePeriod(User $user, Period $period): bool
    {
        return $this->isAuthorized($user, $period);
    }

    public function deletePeriod(User $user, Period $period): bool
    {
        return $this->isAuthorized($user, $period);
    }

    private function isAuthorized(User $user, Period $period): bool
    {
        $history = $period->history;

        return $history->owner_id === $user->id || $history->isPlayer($user);
    }
}
