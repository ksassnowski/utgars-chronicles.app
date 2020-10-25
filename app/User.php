<?php declare(strict_types=1);

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 */
class User extends Authenticatable implements MicroscopePlayer
{
    use Notifiable, HasFactory;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'owner_id');
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(History::class);
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
