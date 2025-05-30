<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App;

use App\Exceptions\RequestAlreadyAnsweredException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ?Carbon $accepted_at
 * @property int     $id
 * @property Lfg     $lfg
 * @property ?Carbon $rejected_at
 * @property User    $user
 */
class LfgRequest extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, LfgRequest>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Lfg, LfgRequest>
     */
    public function lfg(): BelongsTo
    {
        return $this->belongsTo(Lfg::class);
    }

    public function isPending(): bool
    {
        return null === $this->accepted_at && null === $this->rejected_at;
    }

    /**
     * @throws RequestAlreadyAnsweredException
     */
    public function accept(): void
    {
        if (!$this->isPending()) {
            throw new RequestAlreadyAnsweredException();
        }

        tap($this, function (self $request): void {
            $request->lfg->addPlayer($this->user);
        })->update(['accepted_at' => now()]);
    }

    /**
     * @throws RequestAlreadyAnsweredException
     */
    public function reject(): void
    {
        if (!$this->isPending()) {
            throw new RequestAlreadyAnsweredException();
        }

        $this->update(['rejected_at' => now()]);
    }
}
