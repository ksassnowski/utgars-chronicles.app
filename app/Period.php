<?php declare(strict_types=1);

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Collection $events
 * @property History $history
 * @property string $name
 * @property int $position
 * @property User $user
 */
class Period extends Model implements Movable
{
    use HasPosition;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $hidden = [
        'history',
    ];

    /** @var array */
    protected $casts = [
        'position' => 'int',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Period $period) {
            // Always sort new periods to the very end.
            $period->position = DB::table('periods')->max('position') + 1;
        });
    }

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('history_id', $this->history->id);
    }
}
