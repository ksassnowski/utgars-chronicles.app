<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'position' => ['required', 'integer', 'min:0'],
        ];
    }

    public function position(): int
    {
        return (int) $this->get('position');
    }
}
