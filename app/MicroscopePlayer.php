<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App;

interface MicroscopePlayer
{
    public function joinGame(History $app, ?string $name = null): void;

    public function getAuthIdentifier();

    public function getName(History $history): string;

    public function isPlayer(History $history): bool;

    public function isGuest(): bool;
}
