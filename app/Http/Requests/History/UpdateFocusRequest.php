<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFocusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
