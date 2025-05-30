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
