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

final class MoveEventRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'position' => ['required', 'integer', 'min:0'],
        ];
    }

    public function position(): int
    {
        /** @phpstan-ignore-next-line */
        return (int) $this->validated('position');
    }
}
