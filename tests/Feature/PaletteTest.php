<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use App\History;
use App\Palette;
use Tests\TestCase;
use App\PaletteType;
use App\Events\ItemAddedToPalette;
use App\Events\PaletteItemUpdated;
use Illuminate\Support\Facades\Event;
use App\Http\Controllers\PaletteController;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\Palette\CreatePaletteItemRequest;
use App\Http\Requests\Palette\UpdatePaletteItemRequest;

class PaletteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @test
     * @dataProvider routeProvider
     */
    public function authenticationTest(string $httpMethod, string $uri): void
    {
        $method = "{$httpMethod}Json";

        /** @var TestResponse $response */
        $response = $this->$method($uri);

        $response->assertUnauthorized();
    }

    public function routeProvider(): Generator
    {
        yield from [
            'add to palette' => ['post', '/histories/1/palette'],
            'edit palette item' => ['put', '/palette/1'],
            'delete palette item' => ['delete', '/palette/1'],
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validateRoutes(string $controller, string $action, string $requestClass): void
    {
        $this->assertActionUsesFormRequest($controller, $action, $requestClass);
    }

    public function validationProvider(): Generator
    {
        yield from [
            'add to palette' => [PaletteController::class, 'store', CreatePaletteItemRequest::class],
            'edit palette item' => [PaletteController::class, 'update', UpdatePaletteItemRequest::class],
        ];
    }

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

    public function authorizationProvider(): Generator
    {
        yield from [
            'add item to palette' => [
                ['name' => '::entry-name::', 'type' => PaletteType::NO],
                '/histories/1/palette',
                'post',
                201,
            ],
            'edit palette item' => [
                ['name' => '::entry-name::', 'type' => PaletteType::NO],
                '/palette/1',
                'put',
                200,
                fn (History $history) => factory(Palette::class)->create(['history_id' => $history->id]),
            ],
            'delete palette item' => [
                [],
                '/palette/1',
                'delete',
                204,
                fn (History $history) => factory(Palette::class)->create(['history_id' => $history->id]),
            ],
        ];
    }

    /** @test */
    public function addToPalette()
    {
        $history = factory(History::class)->create();

        $response = $this->actingAs($history->owner)->postJson(route('history.palette.store', $history), [
            'name' => '::entry-name::',
            'type' => PaletteType::YES,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('palettes', [
            'history_id' => $history->id,
            'name' => '::entry-name::',
            'type' => PaletteType::YES,
        ]);
        Event::assertDispatched(
            ItemAddedToPalette::class,
            function (ItemAddedToPalette $event) use ($history) {
                return $event->history->id === $history->id
                    && $event->item->name === '::entry-name::'
                    && $event->item->type === PaletteType::YES;
            }
        );
    }

    /** @test */
    public function editPaletteItem(): void
    {
        /** @var History $history */
        $history = factory(History::class)->create();
        $item = $history->addToPalette('::old-name::', PaletteType::YES);

        $response = $this->actingAs($history->owner)->putJson(route('palette.update', $item), [
            'name' => '::new-name::',
            'type' => PaletteType::NO,
        ]);

        $response->assertOk();
        $item->refresh();
        $this->assertEquals('::new-name::', $item->name);
        $this->assertEquals(PaletteType::NO, $item->type);
        Event::assertDispatched(
            PaletteItemUpdated::class,
            fn (PaletteItemUpdated $event) => $event->itemId === $item->id && $event->history->id === $history->id
        );
    }

    /** @test */
    public function deletePaletteItem()
    {
        $history = factory(History::class)->create();
        $item = $history->addToPalette('::old-name::', PaletteType::YES);

        $response = $this->actingAs($history->owner)->deleteJson(route('palette.delete', $item));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('palettes', [
            'id' => $item->id,
        ]);
    }
}
