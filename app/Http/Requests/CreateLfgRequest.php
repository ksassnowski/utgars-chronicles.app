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

use Illuminate\Foundation\Http\FormRequest;

class CreateLfgRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'slots' => ['required', 'integer', 'min:2'],
        ];
    }
}
