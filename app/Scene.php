<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property Event   $event
 * @property History $history
 */
class Scene extends Model implements Movable
{
    use HasPosition;
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $hidden = [
        'event',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'position' => 'int',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

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

    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('event_id', $this->event->id);
    }
}
