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

namespace App\Policies;

use App\History;
use App\User;

class HistoryPolicy
{
    public function updateVisibility(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    public function deleteHistory(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    public function showGame(User $user, History $history): bool
    {
        return $this->isPlayer($user, $history);
    }

    public function showHistory(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
    }

    public function updateHistory(User $user, History $history): bool
    {
        return $this->ownsHistory($user, $history);
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
