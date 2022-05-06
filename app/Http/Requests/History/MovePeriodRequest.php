<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class MovePeriodRequest extends FormRequest
{
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
