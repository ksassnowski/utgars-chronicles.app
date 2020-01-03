<?php declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Period $period
 * @property int $position
 */
class Event extends Model implements Movable
{
    use HasPosition;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $hidden = [
        'period',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Event $event) {
            // Always sort new events to the very end.
            $event->position = DB::table('events')
                    ->where('period_id', $event->period_id)
                    ->max('position') + 1;
        });
    }

    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('period_id', $this->period->id);
    }
}
