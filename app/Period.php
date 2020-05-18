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

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('position', 'ASC');
    }

    public function insertEvent(array $attributes): Event
    {
        Event::where('period_id', $this->id)
            ->where('position', '>=', $attributes['position'])
            ->update(['position' => DB::raw('position + 1')]);

        return $this->events()->create($attributes);
    }

    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('history_id', $this->history->id);
    }
}
