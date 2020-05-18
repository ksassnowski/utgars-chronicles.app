<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use App\Rules\ValidPosition;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
                new ValidPosition('periods', 'history_id', $this->route('history')->id),
            ],
        ];
    }
}
