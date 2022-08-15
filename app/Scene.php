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
use Illuminate\Support\Facades\DB;

/**
 * @property Event         $event
 * @property History       $history
 * @property null|CardType $type
 */
class Scene extends Model implements Movable
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
        'event',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'type' => CardType::class,
        'position' => 'int',
    ];

    /**
     * @return BelongsTo<Event, Scene>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<History, Scene>
     */
    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(static function (self $scene): void {
            $scene->position = DB::table('scenes')
                ->where('event_id', $scene->event_id)
                ->max('position') + 1;
        });
    }

    /**
     * @param Builder<Scene> $query
     */
    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('event_id', $this->event->id);
    }
}
