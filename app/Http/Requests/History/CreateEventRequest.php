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

namespace App\Http\Requests\History;

use App\Rules\ValidPosition;
use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreateEventRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'type' => ['required', Rule::in([Type::DARK, Type::LIGHT])],
            'position' => [
                'required',
                'integer',
                'min:0',
                /** @phpstan-ignore-next-line */
                new ValidPosition('events', 'period_id', $this->route('period')->id),
            ],
        ];
    }
}
