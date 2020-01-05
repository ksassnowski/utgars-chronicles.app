<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'confirmed', 'min:8', 'string'],
        ];
    }

    public function password(): string
    {
        return $this->post('password');
    }
}
