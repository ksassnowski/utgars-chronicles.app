<?php declare(strict_types=1);

namespace Tests;

use Generator;

trait ValidateRoutesTest
{
    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validateRoutes(string $controller, string $action, string $requestClass): void
    {
        $this->assertActionUsesFormRequest($controller, $action, $requestClass);
    }

    abstract public function validationProvider(): Generator;
}
