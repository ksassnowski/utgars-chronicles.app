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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property Collection<int, Event> $events
 * @property History                $history
 * @property int                    $id
 * @property string                 $name
 * @property int                    $position
 * @property User                   $user
 */
class Period extends Model implements Movable
{
    use HasPosition;
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'history',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'int',
    ];

    /**
     * @return BelongsTo<History, Period>
     */
    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    /**
     * @return HasMany<Event>
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('position', 'ASC');
    }

    public function insertEvent(array $attributes): Event
    {
        Event::where('period_id', $this->id)
            ->where('position', '>=', $attributes['position'])
            ->update(['position' => DB::raw('position + 1')]);

        return $this->events()->create(\array_merge($attributes, [
            'history_id' => $this->history->id,
        ]));
    }

    protected static function boot(): void
    {
        parent::boot();

        self::deleted(static function (self $period): void {
            self::where('history_id', $period->history_id)
                ->where('position', '>', $period->position)
                ->update([
                    'position' => DB::raw('position - 1'),
                ]);
        });
    }

    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('history_id', $this->history->id);
    }
}
