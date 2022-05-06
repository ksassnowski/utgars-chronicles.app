<?php

declare(strict_types=1);

namespace Tests\Unit\Request;

use App\Http\Requests\Legacy\CreateLegacyRequest;
use Illuminate\Foundation\Http\FormRequest;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CreateLegacyRequestTest extends TestCase
{
    use FormRequestTest;

    protected function getRequest(): FormRequest
    {
        return new CreateLegacyRequest();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
