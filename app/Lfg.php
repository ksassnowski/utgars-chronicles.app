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

use App\Exceptions\AlreadyRequestedToJoinException;
use App\Exceptions\GameAlreadyStartedException;
use App\Exceptions\LfgAlreadyFullException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int        $availableSlots
 * @property User       $owner
 * @property Collection $pendingRequests
 * @property Collection $requests
 * @property Carbon     $start_date
 * @property int        $user_id
 */
class Lfg extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'availableSlots',
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
     * @throws AlreadyRequestedToJoinException|GameAlreadyStartedException|LfgAlreadyFullException
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
        return 0 === $this->availableSlots;
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
