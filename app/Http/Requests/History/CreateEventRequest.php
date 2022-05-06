<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Rules\ValidPosition;
use App\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in([Type::DARK, Type::LIGHT])],
            'position' => [
                'required',
                'integer',
                'min:0',
                /** @phpstan-ignore-next-line */
                new ValidPosition('events', 'period_id', $this->route('period')->id),
            ],
        ];
    }
}
