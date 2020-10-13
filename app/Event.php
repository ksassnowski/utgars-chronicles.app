<?php declare(strict_types=1);

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property Period $period
 * @property int $position
 */
class Event extends Model implements Movable
{
    use HasPosition, HasFactory;

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $hidden = [
        'period',
    ];

    /** @var array */
    protected $casts = [
        'position' => 'int',
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleted(function (Event $event) {
            Event::where('period_id', $event->period_id)
                ->where('position', '>', $event->position)
                ->update([
                    'position' => DB::raw('position - 1')
                ]);
        });
    }

    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class)->orderBy('position', 'ASC');
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
