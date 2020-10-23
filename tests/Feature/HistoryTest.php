<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\ValidateRoutesTest;
use Tests\AuthorizeHistoryTest;
use App\Events\HistorySeedUpdated;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use App\Http\Requests\History\UpdateSeedRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\History\UpdateSeedController;

class HistoryTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, ValidateRoutesTest, AuthorizeHistoryTest;

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

        $this->user->refresh();
        $this->assertTrue($this->user->histories->contains('name', '::history-name::'));
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
            'update seed' => [
                'patch',
                fn (History $history) => route('history.update-seed', $history),
                fn () => History::factory()->create(),
            ],
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

    public function authorizationProvider(): Generator
    {
        yield from [
            'update history seed' => [
                ['name' => '::new-name::'],
                fn (History $history) => route('history.update-seed', $history),
                'patch',
                200,
            ]
        ];
    }
}
