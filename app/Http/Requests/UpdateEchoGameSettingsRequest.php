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

namespace App\Http\Requests;

use App\MicroscopeEcho\AgentPowers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEchoGameSettingsRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'faction_1_name' => ['nullable', 'string', 'max:255'],
            'faction_1_description' => ['nullable', 'string'],
            'faction_2_name' => ['nullable', 'string', 'max:255'],
            'faction_2_description' => ['nullable', 'string'],
            'agent_powers' => ['nullable', new Enum(AgentPowers::class)],
        ];
    }
}
