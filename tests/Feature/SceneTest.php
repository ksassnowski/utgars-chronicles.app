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

namespace Tests\Feature;

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Http\Controllers\Event\CreateSceneController;
use App\Http\Controllers\Scene\UpdateSceneController;
use App\Http\Requests\History\CreateSceneRequest;
use App\Http\Requests\History\UpdateSceneRequest;
use App\Scene;
use App\Type;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;
use function route;

/**
 * @internal
 */
final class SceneTest extends TestCase
{
    use RefreshDatabase;
    use ScopedRouteTest;
    use GameRouteTest;
    use ValidateRoutesTest;

    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake([
            BoardUpdated::class,
        ]);

        $this->event = Event::factory()->create();
        $this->user = $this->event->history->owner;
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'create scene' => [
                'post',
                static fn () => Event::factory()->create(),
                static fn (History $history, Event $event) => route('events.scenes.store', [$history, $event]),
            ],
            'update scene' => [
                'put',
                static fn () => Scene::factory()->create(),
                static fn (History $history, Scene $scene) => route('scenes.update', [$history, $scene]),
            ],
            'delete scene' => [
                'delete',
                static fn () => Scene::factory()->create(),
                static fn (History $history, Scene $scene) => route('scenes.delete', [$history, $scene]),
            ],
        ];
    }

    public function validationProvider(): Generator
    {
        yield from [
            'create scene' => [
                CreateSceneController::class,
                '__invoke',
                CreateSceneRequest::class,
            ],
            'update scene' => [
                UpdateSceneController::class,
                '__invoke',
                UpdateSceneRequest::class,
            ],
        ];
    }

    public function testCreateScene(): void
    {
        $response = $this->login()->postJson(route('events.scenes.store', [$this->event->history, $this->event]), [
            'question' => '::question::',
            'scene' => '::scene::',
            'answer' => '::answer::',
            'type' => Type::DARK,
            'position' => 1,
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('scenes', [
            'question' => '::question::',
            'scene' => '::scene::',
            'answer' => '::answer::',
            'type' => Type::DARK,
            'position' => 1,
            'event_id' => $this->event->id,
            'history_id' => $this->event->history_id,
        ]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    public function testUpdateScene(): void
    {
        $scene = Scene::factory()->create([
            'event_id' => $this->event->id,
            'history_id' => $this->event->history_id,
        ]);

        $response = $this->login()
            ->putJson(route('scenes.update', [$scene->history, $scene]), [
                'question' => '::new-question::',
                'scene' => '::new-scene::',
                'answer' => '::new-answer::',
                'type' => Type::LIGHT,
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $scene->refresh();
        self::assertEquals('::new-question::', $scene->question);
        self::assertEquals('::new-scene::', $scene->scene);
        self::assertEquals('::new-answer::', $scene->answer);
        self::assertEquals(Type::LIGHT, $scene->type);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    public function testDeleteScene(): void
    {
        $scene = Scene::factory()->create([
            'history_id' => $this->event->history_id,
        ]);

        $response = $this->login()->deleteJson(route('scenes.delete', [$scene->history, $scene]));

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('scenes', [
            'id' => $scene->id,
        ]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    public function gameRouteProvider(): Generator
    {
        yield ['events.scenes.store'];

        yield ['scenes.update'];

        yield ['scenes.delete'];

        yield ['scenes.move'];
    }
}
