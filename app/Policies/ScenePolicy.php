<?php declare(strict_types=1);

namespace App\Policies;

use App\User;
use App\Scene;
use App\History;

final class ScenePolicy
{
    public function updateScene(User $user, Scene $scene): bool
    {
        return $this->isAuthorized($user, $scene);
    }

    public function deleteScene(User $user, Scene $scene): bool
    {
        return $this->isAuthorized($user, $scene);
    }

    public function moveScene(User $user, Scene $scene): bool
    {
        return $this->isAuthorized($user, $scene);
    }

    private function isAuthorized(User $user, Scene $scene): bool
    {
        /** @var History $history */
        $history = $scene->history;

        return $history->owner_id === $user->id || $history->isPlayer($user);
    }
}
