<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Scene;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\ScopedRouteTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoveSceneTest extends TestCase
{
    use RefreshDatabase, ScopedRouteTest;

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'move scene' => [
                'post',
                fn () => Scene::factory()->create(),
                fn (History $history, Scene $scene) => route('scenes.move', [$history, $scene]),
            ],
        ];
    }
}
