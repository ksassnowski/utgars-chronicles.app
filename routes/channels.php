<?php

declare(strict_types=1);

use App\History;
use App\MicroscopePlayer;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('history.{history}', function (MicroscopePlayer $player, History $history) {
    if (! $history->public && $player->isGuest()) {
        return null;
    }

    if (! $player->isPlayer($history)) {
        return null;
    }

    return [
        'id' => $player->getAuthIdentifier(),
        'name' => $player->getName($history),
    ];
}, ['guards' => 'microscope']);
