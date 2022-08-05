<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property null|AgentPowers $agent_powers
 * @property Carbon           $created_at
 * @property null|string      $faction_1_description
 * @property null|string      $faction_1_name
 * @property null|string      $faction_2_description
 * @property null|string      $faction_2_name
 * @property History          $history
 * @property int              $history_id
 * @property int              $id
 * @property Carbon           $updated_at
 */
final class EchoGameSettings extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'agent_powers' => AgentPowers::class,
    ];

    /**
     * @return BelongsTo<History, EchoGameSettings>
     */
    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }
}
