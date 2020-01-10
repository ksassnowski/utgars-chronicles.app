<?php declare(strict_types=1);

namespace App\Http\Requests\Legacy;

use Illuminate\Foundation\Http\FormRequest;

class CreateLegacyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
