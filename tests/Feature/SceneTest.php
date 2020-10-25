<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\Event;
use App\Scene;
use Generator;
use App\History;
use function route;
use Tests\TestCase;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use App\Events\BoardUpdated;
use Tests\ValidateRoutesTest;
use App\Http\Requests\History\CreateSceneRequest;
use App\Http\Requests\History\UpdateSceneRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;
use App\Http\Controllers\Event\CreateSceneController;
use App\Http\Controllers\Scene\UpdateSceneController;

class SceneTest extends TestCase
{
    use RefreshDatabase, ScopedRouteTest, GameRouteTest, ValidateRoutesTest;

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
                fn () => Event::factory()->create(),
                fn (History $history, Event $event) => route('events.scenes.store', [$history, $event]),
            ],
            'update scene' => [
                'put',
                fn () => Scene::factory()->create(),
                fn (History $history, Scene $scene) => route('scenes.update', [$history, $scene]),
            ],
            'delete scene' => [
                'delete',
                fn () => Scene::factory()->create(),
                fn (History $history, Scene $scene) => route('scenes.delete', [$history, $scene]),
            ]
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

    /** @test */
    public function createScene(): void
    {
        $response = $this->login()->postJson(route('events.scenes.store', [$this->event->history, $this->event]), [
            'question' => '::question::',
            'scene' => '::scene::',
            'answer' => '::answer::',
            'type' => Type::DARK,
            'position' => 1,
        ]);

        $response->assertStatus(201);
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

    /** @test */
    public function updateScene(): void
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
                'type' => Type::LIGHT
            ]);

        $response->assertOk();
        $scene->refresh();
        $this->assertEquals('::new-question::', $scene->question);
        $this->assertEquals('::new-scene::', $scene->scene);
        $this->assertEquals('::new-answer::', $scene->answer);
        $this->assertEquals(Type::LIGHT, $scene->type);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /** @test */
    public function deleteScene(): void
    {
        $scene = Scene::factory()->create([
            'history_id' => $this->event->history_id,
        ]);

        $response = $this->login()->deleteJson(route('scenes.delete', [$scene->history, $scene]));

        $response->assertStatus(204);
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
