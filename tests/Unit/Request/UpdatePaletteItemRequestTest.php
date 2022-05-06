<?php

declare(strict_types=1);

namespace Tests\Unit\Request;

use App\Http\Requests\Palette\UpdatePaletteItemRequest;
use App\PaletteType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UpdatePaletteItemRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new UpdatePaletteItemRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in([PaletteType::YES, PaletteType::NO])],
        ];
    }
}
