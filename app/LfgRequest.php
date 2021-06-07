<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\RequestAlreadyAcceptedException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property Lfg $lfg
 * @property User $user
 * @property ?Carbon $accepted_at
 * @property ?Carbon $rejected_at
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

    /**
     * @throws RequestAlreadyAcceptedException
     */
    public function accept(): void
    {
        if ($this->accepted_at !== null) {
            throw new RequestAlreadyAcceptedException();
        }

        tap($this, function (LfgRequest $request) {
            $request->lfg->addPlayer($this->user);
        })->update(['accepted_at' => now()]);
    }
}
