<?php declare(strict_types=1);

namespace Tests;

use Generator;
use Illuminate\Testing\TestResponse;

trait AuthenticatedRoutesTest
{
    /**
     * @test
     * @dataProvider authenticatedRoutesProvider
     */
    public function authenticationTest(string $httpMethod, string $uri): void
    {
        $method = "{$httpMethod}Json";

        /** @var TestResponse $response */
        $response = $this->$method($uri);

        $response->assertUnauthorized();
    }

    abstract public function authenticatedRoutesProvider(): Generator;
}
