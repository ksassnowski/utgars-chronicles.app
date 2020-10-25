<?php declare(strict_types=1);

namespace App;

interface MicroscopePlayer
{
    public function joinGame(History $app, ?string $name = null): void;

    public function getAuthIdentifier();

    public function getName(History $history): string;

    public function isPlayer(History $history): bool;

    public function isGuest(): bool;
}
