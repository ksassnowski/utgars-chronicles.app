<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property "yes"|"no" $type
 * @property History $history
 */
class Palette extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @return BelongsTo<History, Palette>
     */
    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }
}
