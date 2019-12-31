<?php declare(strict_types=1);

namespace Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Legacy\UpdateLegacyRequest;

class UpdateLegacyRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new UpdateLegacyRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
