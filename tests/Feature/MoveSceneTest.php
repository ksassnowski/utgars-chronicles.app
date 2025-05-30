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

namespace Tests\Feature;

use App\History;
use App\Scene;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ScopedRouteTest;
use Tests\TestCase;

/**
 * @internal
 */
final class MoveSceneTest extends TestCase
{
    use RefreshDatabase;
    use ScopedRouteTest;

    public static function scopedRouteProvider(): \Generator
    {
        yield from [
            'move scene' => [
                'post',
                static fn () => Scene::factory()->create(),
                static fn (History $history, Scene $scene) => route('scenes.move', [$history, $scene]),
            ],
        ];
    }
}
