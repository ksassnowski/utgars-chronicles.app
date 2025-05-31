<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Http\Requests;

use App\CardType;
use App\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class CreateEchoRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(CardType::class)],
            'cause' => [
                'required',
                Rule::exists('events', 'id')
                    ->where('history_id', $this->route('history')->id),
            ],
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

    public function cause(): Event
    {
        return Event::findOrFail($this->post('cause'));
    }
}
