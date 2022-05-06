<?php

declare(strict_types=1);

namespace Tests;

use Generator;
use Illuminate\Testing\TestResponse;

trait AuthenticatedRoutesTest
{
    /**
     * @test
     * @dataProvider authenticatedRoutesProvider
     *
     * @param mixed $uri
     */
    public function authenticationTest(string $httpMethod, $uri, ?callable $setup = null): void
    {
        $method = "{$httpMethod}Json";

        $entity = null;

        if (null !== $setup) {
            $entity = $setup();
        }

        $route = \is_callable($uri) ? $uri($entity) : $uri;

        /** @var TestResponse $response */
        $response = $this->{$method}($route);

        $response->assertUnauthorized();
    }

    abstract public function authenticatedRoutesProvider(): Generator;
}
