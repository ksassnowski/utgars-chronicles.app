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

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

trait GameRouteTest
{
    #[DataProvider('gameRouteProvider')]
    #[Test()]
    public function routeUsesMicroscopeMiddleware(string $routeName): void
    {
        $this->assertRouteUsesMiddleware($routeName, ['microscope']);
    }

    abstract public static function gameRouteProvider(): \Generator;
}
