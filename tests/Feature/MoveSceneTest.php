<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\History;
use App\Scene;
use Generator;
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

    public function scopedRouteProvider(): Generator
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
