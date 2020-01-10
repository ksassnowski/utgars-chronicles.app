<?php declare(strict_types=1);

namespace Tests\Unit\Request;

use App\PaletteType;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Palette\CreatePaletteItemRequest;

class CreatePaletteItemRequestTest extends TestCase
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
