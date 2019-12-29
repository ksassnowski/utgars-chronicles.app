<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePeriodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in(['light', 'dark'])],
        ];
    }
}
