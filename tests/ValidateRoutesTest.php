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
