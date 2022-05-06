<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Events\HistorySeedUpdated;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use App\History;
use App\Http\Controllers\History\UpdateSeedController;
use App\Http\Requests\History\UpdateSeedRequest;
use App\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\AuthenticatedRoutesTest;
use Tests\GameRouteTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;

/**
 * @internal
 */
final class HistoryTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoutesTest;
    use ValidateRoutesTest;
    use GameRouteTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function testCreateANewHistoryForUser(): void
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

    public function testCreatePublicHistory(): void
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

    public function testNameIsRequired(): void
    {
        $response = $this->login()->post(route('history.store'), []);

        $response->assertSessionHasErrors(['name']);
    }

    public function testChangePrivateGameToPublic(): void
    {
        $history = History::factory()->create();

        $this->actingAs($history->owner)
            ->patchJson(route('history.visibility', $history), [
                'public' => true,
            ]);

        $history->refresh();
        self::assertTrue($history->public);
    }

    public function testChangePublicGameToPrivate(): void
    {
        $history = History::factory()->public()->create();

        $this->actingAs($history->owner)
            ->patchJson(route('history.visibility', $history), [
                'public' => false,
            ]);

        $history->refresh();
        self::assertFalse($history->public);
    }

    public function testUpdateHistory(): void
    {
        $this->login();
        $history = History::factory()->create([
            'owner_id' => $this->user->id,
            'name' => '::old-name::',
        ]);

        $response = $this->patch(route('history.update-seed', $history), [
            'name' => '::new-name::',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $history->refresh();
        self::assertEquals('::new-name::', $history->name);
        Event::assertDispatched(
            HistorySeedUpdated::class,
            static fn (HistorySeedUpdated $event) => $event->history->id === $history->id && '::new-name::' === $event->history->name,
        );
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'create history' => ['post', '/histories'],
            'delete history' => ['delete', '/histories/1'],
        ];
    }

    public function testAddNewPlayerToHistory(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $player = User::factory()->create();

        self::assertNull($history->players->first(static fn (User $p) => $p->id === $player->id));

        $history->addPlayer($player);

        $history->refresh();
        self::assertNotNull($history->players->first(static fn (User $p) => $p->id === $player->id));
    }

    public function testCantAddSamePlayerTwice(): void
    {
        $this->expectException(UserIsAlreadyPlayerInHistory::class);

        $history = History::factory()->create();
        $player = User::factory()->create();
        $history->addPlayer($player);

        $history->addPlayer($player);
    }

    public function testDeleteHistory(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->delete(route('history.delete', $history));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('histories', [
            'id' => $history->id,
        ]);
    }

    public function testOnlyOwnerCanDeleteHistory(): void
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
