<?php declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\DB;
use App\Exceptions\UserIsNotAPlayer;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use App\Exceptions\OwnerCannotJoinOwnGameAsPlayer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property User owner
 * @property int owner_id
 */
class History extends Model
{
    use HasFactory;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $casts = [
        'owner_id' => 'int',
        'public' => 'bool',
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
        return $this->hasMany(Period::class)->orderBy('position', 'ASC');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('position', 'ASC');
    }

    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class)->orderBy('position', 'ASC');
    }

    public function foci(): HasMany
    {
        return $this->hasMany(Focus::class)->latest();
    }

    public function palettes(): HasMany
    {
        return $this->hasMany(Palette::class)->latest();
    }

    public function legacies(): HasMany
    {
        return $this->hasMany(Legacy::class)->latest();
    }

    public function insertPeriod(array $attributes): Period
    {
        Period::where('history_id', $this->id)
           ->where('position', '>=', $attributes['position'])
           ->update(['position' => DB::raw('position + 1')]);

        return $this->periods()->create($attributes);
    }

    /**
     * @throws UserIsAlreadyPlayerInHistory
     * @throws OwnerCannotJoinOwnGameAsPlayer
     */
    public function addPlayer(User $user): void
    {
        if ($this->owner->is($user)) {
            throw new OwnerCannotJoinOwnGameAsPlayer();
        }

        if ($this->isPlayer($user)) {
            throw new UserIsAlreadyPlayerInHistory();
        }

        $this->players()->attach($user->id);
    }

    public function removePlayer(User $player): void
    {
        if (!$this->isPlayer($player)) {
            throw new UserIsNotAPlayer();
        }
        $this->players()->detach($player->id);
    }

    public function isPlayer(User $user): bool
    {
        return $this->players()->where('user_id', $user->id)->exists();
    }

    public function defineFocus(string $name): Focus
    {
        return $this->foci()->create(['name' => $name]);
    }

    public function addToPalette(string $description, string $type): Palette
    {
        return $this->palettes()->create([
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
