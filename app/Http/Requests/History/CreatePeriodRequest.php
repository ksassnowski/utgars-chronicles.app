<?php

declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Rules\ValidPosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePeriodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in(['light', 'dark'])],
            'position' => [
                'required',
                'integer',
                'min:0',
                /** @phpstan-ignore-next-line */
                new ValidPosition('periods', 'history_id', $this->route('history')->id),
            ],
        ];
    }
}
