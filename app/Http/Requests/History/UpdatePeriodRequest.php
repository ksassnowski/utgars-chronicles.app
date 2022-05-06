<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeriodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => Rule::in([Type::LIGHT, Type::DARK]),
            'name' => 'required',
        ];
    }
}
