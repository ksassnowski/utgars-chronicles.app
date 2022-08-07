<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\CardType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class CreateInterventionRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(CardType::class)],
        ];
    }

    public function name(): string
    {
        /** @phpstan-ignore-next-line */
        return $this->validated('name');
    }

    public function type(): CardType
    {
        return CardType::from($this->validated('type'));
    }
}
