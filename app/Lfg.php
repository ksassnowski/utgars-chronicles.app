<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\LfgAlreadyFullException;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\GameAlreadyStartedException;
use App\Exceptions\AlreadyRequestedToJoinException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Carbon $start_date
 * @property int $availableSlots
 * @property User $owner
 * @property int $user_id
 * @property Collection $requests
 * @property Collection $pendingRequests
 */
class Lfg extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'availableSlots'
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(LfgRequest::class);
    }

    public function pendingRequests(): HasMany
    {
        return $this->requests()->whereNull(['accepted_at', 'rejected_at']);
    }

    /**
     * @throws LfgAlreadyFullException|GameAlreadyStartedException|AlreadyRequestedToJoinException
     */
    public function requestToJoin(User $user, ?string $message): LfgRequest
    {
        if ($this->hasStarted()) {
            throw new GameAlreadyStartedException();
        }

        if ($this->isFull()) {
            throw new LfgAlreadyFullException();
        }

        $exists = $this->requests()->where('user_id', $user->id)->exists();

        if ($exists) {
            throw new AlreadyRequestedToJoinException();
        }

        return $this->requests()->create([
            'user_id' => $user->id,
            'message' => $message,
        ]);
    }

    public function addPlayer(User $player): void
    {
        $this->users()->attach([$player->id]);
    }

    public function isFull(): bool
    {
        return $this->availableSlots === 0;
    }

    public function hasStarted(): bool
    {
        return $this->start_date->isBefore(now());
    }

    public function clearPendingRequests(): void
    {
        $this->pendingRequests()->delete();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return $this->slots - ($this->users()->count() + 1);
    }
}
