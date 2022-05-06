<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSceneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => 'required',
            'scene' => 'nullable',
            'answer' => 'nullable',
            'type' => ['nullable', Rule::in([Type::LIGHT, Type::DARK])],
        ];
    }
}
