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

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property History                $history
 * @property int                    $id
 * @property string                 $name
 * @property Period                 $period
 * @property int                    $position
 * @property Collection<int, Scene> $scenes
 * @property CardType               $type
 */
class Event extends Model implements Movable
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
        'period',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => CardType::class,
        'position' => 'int',
    ];

    /**
     * @return HasMany<Scene>
     */
    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class)->orderBy('position', 'ASC');
    }

    /**
     * @return BelongsTo<Period, Event>
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * @return BelongsTo<History, Event>
     */
    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        self::deleted(static function (self $event): void {
            self::where('period_id', $event->period_id)
                ->where('position', '>', $event->position)
                ->update([
                    'position' => DB::raw('position - 1'),
                ]);
        });
    }

    /**
     * @param Builder<Event> $query
     */
    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('period_id', $this->period->id);
    }
}
