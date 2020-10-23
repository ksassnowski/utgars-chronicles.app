<?php declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property History $history
 * @property Event $event
 */
class Scene extends Model implements Movable
{
    use HasPosition, HasFactory;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $hidden = [
        'event',
    ];

    /** @var array  */
    protected $casts = [
        'position' => 'int',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Scene $scene) {
            $scene->position = DB::table('scenes')
                    ->where('event_id', $scene->event_id)
                    ->max('position') + 1;
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    protected function limitElementsToMove(Builder $query): void
    {
        $query->where('event_id', $this->event->id);
    }
}
