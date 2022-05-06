<?php

declare(strict_types=1);

namespace Tests\Unit\Request;

use App\Http\Requests\SubmitFeedbackRequest;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SubmitFeedbackRequestTest extends TestCase
{
    public function testValidationRules(): void
    {
        $request = new SubmitFeedbackRequest();

        self::assertEquals([
            'message' => ['required', 'string'],
        ], $request->rules());
    }
}
