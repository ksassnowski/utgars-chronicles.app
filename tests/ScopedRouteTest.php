<?php declare(strict_types=1);

namespace Tests;

use Generator;
use App\History;

trait ScopedRouteTest
{
    /**
     * @test
     * @dataProvider scopedRouteProvider
     */
    public function routeIsCorrectlyScoped(
        string $httpMethod,
        callable $setup,
        callable $getRoute
    ): void {
        $history = History::factory()->create();
        $entity = $setup();
        $method = $httpMethod . 'Json';

        $this->actingAs($history->owner)
            // The payload doesn't matter because we should never get to this point
            ->{$method}($getRoute($history, $entity), [])
            ->assertNotFound();
    }

    abstract public function scopedRouteProvider(): Generator;
}
