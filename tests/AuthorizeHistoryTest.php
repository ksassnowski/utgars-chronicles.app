<?php declare(strict_types=1);

namespace Tests;

use App\User;
use Generator;
use App\History;
use Illuminate\Testing\TestResponse;

trait AuthorizeHistoryTest
{
    /**
     * @test
     * @dataProvider authorizationProvider
     */
    public function authorizationTest(array $payload, string $route, string $httpMethod, int $status, ?callable $setup = null): void
    {
        $method = "{$httpMethod}Json";
        /** @var History $history */
        $history = factory(History::class)->create();

        if ($setup !== null) {
            $setup($history);
        }

        [$player, $notAPlayer] = factory(User::class, 2)->create();
        $history->addPlayer($player);

        /** @var TestResponse $response */
        $response = $this->actingAs($notAPlayer)->$method($route, $payload);
        $response->assertForbidden();

        $response = $this->actingAs($player)->$method($route, $payload);
        $response->assertStatus($status);
    }

    abstract public function authorizationProvider(): Generator;
}
