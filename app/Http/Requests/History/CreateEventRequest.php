<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Type;
use App\Rules\ValidPosition;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
                new ValidPosition('events', 'period_id', $this->route('period')->id)
            ]
        ];
    }
}
