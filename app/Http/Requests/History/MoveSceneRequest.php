<?php declare(strict_types=1);

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class MoveSceneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'position' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function position(): int
    {
        return (int) $this->get('position');
    }
}
