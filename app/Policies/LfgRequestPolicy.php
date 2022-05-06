<?php

declare(strict_types=1);

namespace App\Policies;

use App\LfgRequest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LfgRequestPolicy
{
    use HandlesAuthorization;

    public function accept(User $user, LfgRequest $request): bool
    {
        return $user->id === $request->lfg->user_id;
    }

    public function reject(User $user, LfgRequest $request): bool
    {
        return $user->id === $request->lfg->user_id;
    }
}
