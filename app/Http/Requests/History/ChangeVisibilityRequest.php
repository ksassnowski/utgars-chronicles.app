<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class ChangeVisibilityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'public' => ['required', 'boolean'],
        ];
    }
}
