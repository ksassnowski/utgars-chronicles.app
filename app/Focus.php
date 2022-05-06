<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property History $history
 */
class Focus extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @return BelongsTo<History, Focus>
     */
    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }
}
