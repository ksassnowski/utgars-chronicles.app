<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property History $history
 */
class Palette extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }
}
