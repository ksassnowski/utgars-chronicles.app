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

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidPosition implements Rule
{
    private int $relatedId;

    private string $table;

    private string $relatedColumn;

    public function __construct(string $table, string $relatedColumn, int $relatedId)
    {
        $this->relatedId = $relatedId;
        $this->table = $table;
        $this->relatedColumn = $relatedColumn;
    }

    public function passes($attribute, $value): bool
    {
        $maxPosition = DB::table($this->table)
            ->where($this->relatedColumn, $this->relatedId)
            ->max('position');

        if (null === $maxPosition) {
            return 1 === $value;
        }

        return 1 >= ($value - $maxPosition);
    }

    public function message(): string
    {
        return 'The :attribute is invalid';
    }
}
