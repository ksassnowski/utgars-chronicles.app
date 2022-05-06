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

final class MovePeriodRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'position' => ['required', 'integer', 'min:1'],
        ];
    }

    public function position(): int
    {
        return (int) $this->json('position');
    }
}
