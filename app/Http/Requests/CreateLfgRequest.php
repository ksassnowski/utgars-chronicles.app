<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLfgRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'slots' => ['required', 'integer', 'min:2'],
        ];
    }
}
