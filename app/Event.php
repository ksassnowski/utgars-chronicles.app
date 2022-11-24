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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property null|Event             $cause
 * @property null|int               $echo_group
 * @property null|int               $echo_group_position
 * @property EventType              $event_type
 * @property History                $history
 * @property int                    $history_id
 * @property int                    $id
 * @property string                 $name
 * @property Period                 $period
 * @property int                    $period_id
 * @property int                    $position
 * @property Collection<int, Scene> $scenes
 * @property CardType               $type
 */
class Event extends Model implements Movable
{
    use HasPosition {
        move as baseMove;
    }
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
        'event_type' => EventType::class,
        'echo_group_position' => 'int',
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

    /**
     * @return BelongsTo<Event, Event>
     */
    public function cause(): BelongsTo
    {
        return $this->belongsTo(self::class, 'event_id');
    }

    public function isRegularEvent(): bool
    {
        return EventType::Event === $this->event_type;
    }

    public function isIntervention(): bool
    {
        return EventType::Intervention === $this->event_type;
    }

    public function isEcho(): bool
    {
        return EventType::Echo === $this->event_type;
    }

    public function belongsToEchoGroup(): bool
    {
        return null !== $this->echo_group;
    }

    public function move(int $position): void
    {
        DB::transaction(function () use ($position) {
            $this->baseMove($position);

            // Make sure we also move all events in the same "stack"
            // to the new position.
            if ($this->echo_group !== null) {
                Event::query()
                    ->where('echo_group', $this->echo_group)
                    ->where('history_id', $this->history_id)
                    ->update(['position' => $position]);
            }
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        self::deleted(static function (self $event): void {
            DB::transaction(static function () use ($event): void {
                self::reorderEventsInEchoGroup($event);

                if (self::hasOtherEventsInGroup($event)) {
                    return;
                }

                self::query()
                    ->where('period_id', $event->period_id)
                    ->where('position', '>', $event->position)
                    ->update([
                        'position' => \DB::raw('position - 1'),
                    ]);
            });
        });
    }

    /**
     * @param Builder<Event> $query
     */
    protected function limitElementsToMove(Builder $query): void
    {
        $query
            ->where('period_id', $this->period->id)
            ->when($this->echo_group !== null, function (Builder $query) {
                $query
                    ->where('echo_group', '!=', $this->echo_group)
                    ->orWhereNull('echo_group');
            });
    }

    private static function reorderEventsInEchoGroup(self $event): void
    {
        if (!$event->belongsToEchoGroup()) {
            return;
        }

        self::query()
            ->where('period_id', $event->period_id)
            ->where('echo_group', $event->echo_group)
            ->where('echo_group_position', '>', $event->echo_group_position)
            ->update([
                'echo_group_position' => DB::raw('echo_group_position - 1'),
            ]);
    }

    private static function hasOtherEventsInGroup(self $event): bool
    {
        if (!$event->belongsToEchoGroup()) {
            return false;
        }

        return self::query()
            ->where('echo_group', $event->echo_group)
            ->exists();
    }
}
