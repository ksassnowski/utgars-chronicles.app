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

use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSceneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => 'required',
            'scene' => 'nullable',
            'answer' => 'nullable',
            'type' => ['nullable', Rule::in([Type::DARK, Type::LIGHT])],
        ];
    }
}
