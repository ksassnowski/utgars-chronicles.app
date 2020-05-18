<?php declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

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

        return ($value - $maxPosition) <= 1;
    }

    public function message(): string
    {
        return 'The :attribute is invalid';
    }
}
