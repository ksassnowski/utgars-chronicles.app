<?php declare(strict_types=1);

use App\User;
use App\Policies\HistoryPolicy;

Broadcast::channel('history.{history}', function (User $user, \App\History $history) {
    if ((new HistoryPolicy())->modifyGame($user, $history)) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return null;
});
