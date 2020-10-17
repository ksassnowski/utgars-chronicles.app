<?php declare(strict_types=1);

namespace Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\History\UpdateFocusRequest;

class UpdateFocusRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new UpdateFocusRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
