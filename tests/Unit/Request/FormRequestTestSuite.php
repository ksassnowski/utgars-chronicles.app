<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Unit\Request;

use Illuminate\Foundation\Http\FormRequest;

trait FormRequestTestSuite
{
    /**
     * @test
     */
    public function validationRules(): void
    {
        $this->assertEquals($this->rules(), $this->getRequest()->rules());
    }

    abstract protected function getRequest(): FormRequest;

    abstract protected function rules(): array;
}
