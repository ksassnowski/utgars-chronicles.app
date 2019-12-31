<?php declare(strict_types=1);

namespace App\Policies;

use App\User;
use App\Palette;

final class PalettePolicy
{
    public function updatePalette(User $user, Palette $palette): bool
    {
        return $this->isAuthorized($user, $palette);
    }

    public function deletePalette(User $user, Palette $palette): bool
    {
        return $this->isAuthorized($user, $palette);
    }

    private function isAuthorized(User $user, Palette $palette): bool
    {
        $history = $palette->history;

        return $history->owner_id === $user->id
            || $history->players()->where('user_id', $user->id)->exists();
    }
}
