<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Unit\Request;

use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\PaletteType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CreatePaletteItemRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new CreatePaletteItemRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in([PaletteType::YES, PaletteType::NO])],
        ];
    }
}
