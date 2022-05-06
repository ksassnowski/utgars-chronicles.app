<?php

declare(strict_types=1);

namespace Tests\Unit\Request;

use Illuminate\Foundation\Http\FormRequest;

trait FormRequestTest
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
