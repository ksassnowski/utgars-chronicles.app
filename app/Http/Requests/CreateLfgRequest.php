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

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateLfgRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'slots' => ['required', 'integer', 'min:2'],
        ];
    }
}
