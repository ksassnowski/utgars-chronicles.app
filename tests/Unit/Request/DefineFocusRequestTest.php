<?php declare(strict_types=1);

namespace Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\History\DefineFocusRequest;

class DefineFocusRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new DefineFocusRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
