<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitFeedbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => ['required', 'string']
        ];
    }

    public function message(): string
    {
        return $this->post('message');
    }
}
