<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in([Type::DARK, Type::LIGHT])],
        ];
    }
}
