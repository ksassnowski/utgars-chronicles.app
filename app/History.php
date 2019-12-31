<?php declare(strict_types=1);

namespace App;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property User owner
 * @property int owner_id
 */
class History extends Model
{
    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $casts = [
        'owner_id' => 'int',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }

    public function focus(): HasMany
    {
        return $this->hasMany(Focus::class);
    }

    public function palette(): HasMany
    {
        return $this->hasMany(Palette::class);
    }

    public function legacies(): HasMany
    {
        return $this->hasMany(Legacy::class);
    }

    public function addPlayer(User $user): void
    {
        if ($this->isPlayer($user)) {
            throw new DomainException('Cannot add same player to history twice.');
        }

        $this->players()->attach($user->id);
    }

    public function isPlayer(User $user): bool
    {
        return $this->players()->where('user_id', $user->id)->exists();
    }

    public function defineFocus(string $name): Focus
    {
        return $this->focus()->create(['name' => $name]);
    }

    public function addToPalette(string $description, string $type): Palette
    {
        return $this->palette()->create([
            'name' => $description,
            'type' => $type,
        ]);
    }

    public function addLegacy(string $name): Legacy
    {
        return $this->legacies()->create([
            'name' => $name,
        ]);
    }
}
