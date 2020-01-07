<?php declare(strict_types=1);

namespace Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\SubmitFeedbackRequest;

class SubmitFeedbackRequestTest extends TestCase
{
    /** @test */
    public function validationRules(): void
    {
        $request = new SubmitFeedbackRequest();

        $this->assertEquals([
            'message' => ['required', 'string'],
        ], $request->rules());
    }
}
