<?php

declare(strict_types=1);

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
