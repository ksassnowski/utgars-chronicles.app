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

use App\History;
use Generator;

trait ScopedRouteTest
{
    /**
     * @test
     * @dataProvider scopedRouteProvider
     */
    public function routeIsCorrectlyScoped(
        string $httpMethod,
        callable $setup,
        callable $getRoute,
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
