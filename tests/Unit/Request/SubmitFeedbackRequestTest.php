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
