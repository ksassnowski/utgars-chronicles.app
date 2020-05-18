<?php declare(strict_types=1);

namespace App;

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

    /** @var array */
    protected $casts = [
        'position' => 'int',
    ];

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
