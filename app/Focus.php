<?php declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property History history
 */
class Focus extends Model
{
    /** @var array */
    protected $guarded = [];

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }
}
