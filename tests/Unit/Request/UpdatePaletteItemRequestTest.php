<?php declare(strict_types=1);

namespace Tests\Unit\Request;

use App\PaletteType;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;

class UpdatePaletteItemRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new UpdatePaletteItemRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'type' => ['required', Rule::in([PaletteType::YES, PaletteType::NO])],
        ];
    }
}
