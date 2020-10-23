<?php declare(strict_types=1);

namespace Tests;

use Generator;

trait GameRouteTest
{
    /**
     * @test
     * @dataProvider gameRouteProvider
     */
    public function routeUsesMicroscopeMiddleware(string $routeName): void
    {
        $this->assertRouteUsesMiddleware($routeName, ['microscope']);
    }

    abstract public function gameRouteProvider(): Generator;
}
