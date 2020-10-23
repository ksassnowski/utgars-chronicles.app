<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Scene;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\ScopedRouteTest;
use Tests\AuthorizeHistoryTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoveSceneTest extends TestCase
{
    use RefreshDatabase, ScopedRouteTest, AuthorizeHistoryTest;

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

    public function authorizationProvider(): Generator
    {
        yield from [
            'move scene' => [
                ['position' => 1],
                fn (Scene $scene) => route('scenes.move', [$scene->history, $scene]),
                'post',
                200,
                fn (History $history) => Scene::factory()->create(['history_id' => $history->id]),
            ]
        ];
    }
}
