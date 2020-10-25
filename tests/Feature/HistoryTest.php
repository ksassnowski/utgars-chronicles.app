<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\GameRouteTest;
use Tests\ValidateRoutesTest;
use App\Events\HistorySeedUpdated;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use App\Http\Requests\History\UpdateSeedRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\History\UpdateSeedController;

class HistoryTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, ValidateRoutesTest, GameRouteTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function createANewHistoryForUser(): void
    {
        $this->login()->post(route('history.store'), [
            'name' => '::history-name::',
        ]);

        $this->assertDatabaseHas('histories', [
            'name' => '::history-name::',
            'public' => false,
            'owner_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function createPublicHistory(): void
    {
        $this->login()->post(route('history.store'), [
            'name' => '::history-name::',
            'public' => true,
        ]);

        $this->assertDatabaseHas('histories', [
            'name' => '::history-name::',
            'public' => true,
            'owner_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function nameIsRequired(): void
    {
        $response = $this->login()->post(route('history.store'), []);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function updateHistory(): void
    {
        $this->login();
        $history = History::factory()->create([
            'owner_id' => $this->user->id,
            'name' => '::old-name::',
        ]);

        $response = $this->patchJson(route('history.update-seed', $history), [
            'name' => '::new-name::',
        ]);

        $response->assertOk();
        $history->refresh();
        $this->assertEquals('::new-name::', $history->name);
        Event::assertDispatched(
            HistorySeedUpdated::class,
            fn (HistorySeedUpdated $event) => $event->history->id === $history->id && $event->history->name === '::new-name::'
        );
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'create history' => ['post', '/histories'],
            'delete history' => ['delete', '/histories/1'],
        ];
    }

    /** @test */
    public function addNewPlayerToHistory(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $player = User::factory()->create();

        $this->assertNull($history->players->first(fn (User $p) => $p->id === $player->id));

        $history->addPlayer($player);

        $history->refresh();
        $this->assertNotNull($history->players->first(fn (User $p) => $p->id === $player->id));
    }

    /** @test */
    public function cantAddSamePlayerTwice(): void
    {
        $this->expectException(UserIsAlreadyPlayerInHistory::class);

        $history = History::factory()->create();
        $player = User::factory()->create();
        $history->addPlayer($player);

        $history->addPlayer($player);
    }

    /** @test */
    public function deleteHistory(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->delete(route('history.delete', $history));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('histories', [
            'id' => $history->id,
        ]);
    }

    /** @test */
    public function onlyOwnerCanDeleteHistory(): void
    {
        $baddie = User::factory()->create();
        $history = History::factory()->create();

        $response = $this->actingAs($baddie)->delete(route('history.delete', $history));

        $response->assertForbidden();
    }

    public function validationProvider(): Generator
    {
        yield from [
            'update history seed' => [
                UpdateSeedController::class,
                '__invoke',
                UpdateSeedRequest::class,
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.update-seed'];
    }
}
