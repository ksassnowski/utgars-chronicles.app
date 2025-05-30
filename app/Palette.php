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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property History $history
 * @property int     $history_id
 * @property int     $id
 * @property string  $name
 * @property string  $type
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
