<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property Collection<int, History> $histories
 * @property Collection<int, History> $games
 * @property Collection<int, Lfg> $lfgs
 */
class User extends Authenticatable implements MicroscopePlayer
{
    use Notifiable;
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany<History>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'owner_id');
    }

    /**
     * @return BelongsToMany<History>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(History::class);
    }

    /**
     * @return HasMany<Lfg>
     */
    public function lfgs(): HasMany
    {
        return $this->hasMany(Lfg::class);
    }

    public function getName(History $history): string
    {
        return $this->name;
    }

    public function isPlayer(History $history): bool
    {
        return $history->owner->is($this) || $history->isPlayer($this);
    }

    public function joinGame(History $history, ?string $name = null): void
    {
        $history->addPlayer($this);
    }

    public function isGuest(): bool
    {
        return false;
    }
}
