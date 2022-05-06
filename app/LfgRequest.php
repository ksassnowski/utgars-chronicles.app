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

use App\Exceptions\RequestAlreadyAnsweredException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ?Carbon $accepted_at
 * @property Lfg     $lfg
 * @property ?Carbon $rejected_at
 * @property User    $user
 */
class LfgRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
        throw_unless($this->isPending(), RequestAlreadyAnsweredException::class);

        tap($this, function (self $request): void {
            $request->lfg->addPlayer($this->user);
        })->update(['accepted_at' => now()]);
    }

    /**
     * @throws RequestAlreadyAnsweredException
     */
    public function reject(): void
    {
        throw_unless($this->isPending(), RequestAlreadyAnsweredException::class);

        $this->update(['rejected_at' => now()]);
    }
}
