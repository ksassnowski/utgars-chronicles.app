<?php declare(strict_types=1);

namespace App;

class AnonymousPlayer implements MicroscopePlayer
{
    private string $id;
    private array $histories;

    public function __construct(string $id, array $histories = [])
    {
        $this->id = $id;
        $this->histories = $histories;
    }

    public function joinGame(History $history, ?string $name = null): void
    {
        $this->histories[$history->id] = $name;
        session()->put('histories', $this->histories);
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getName(History $history): string
    {
        return $this->histories[$history->id];
    }

    public function isPlayer(History $history): bool
    {
        return array_key_exists($history->id, $this->histories);
    }

    public function isGuest(): bool
    {
        return true;
    }
}
