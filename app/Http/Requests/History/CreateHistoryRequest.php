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

use Illuminate\Foundation\Http\FormRequest;

class CreateHistoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'public' => ['nullable', 'boolean'],
        ];
    }
}
