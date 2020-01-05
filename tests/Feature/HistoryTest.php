<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use Generator;
use App\History;
use Tests\TestCase;
use Tests\AuthenticatedRoutesTest;
use App\Exceptions\UserIsAlreadyPlayerInHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest;

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
        $history = factory(History::class)->create([
            'owner_id' => $this->user->id,
            'name' => '::old-name::',
        ]);

        $response = $this->put(route('history.update', $history), [
            'name' => '::new-name::',
        ]);

        $response->assertRedirect();
        $history->refresh();
        $this->assertEquals('::new-name::', $history->name);
    }

    /** @test */
    public function updateValidationCheck(): void
    {
        $this->login();
        $history = factory(History::class)->create([
            'owner_id' => $this->user->id,
        ]);

        $response = $this->put(route('history.update', $history), []);

        $response->assertSessionHasErrors(['name']);
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'create history' => ['post', '/histories'],
            'update history' => ['put', '/histories/1'],
            'delete history' => ['delete', '/histories/1'],
        ];
    }

    /** @test */
    public function canOnlyUpdateOwnHistories(): void
    {
        $history = factory(History::class)->create();
        $otherUser = factory(User::class)->create();

        $response = $this->actingAs($otherUser)->put(route('history.update', $history), []);

        $response->assertForbidden();
    }

    /** @test */
    public function addNewPlayerToHistory(): void
    {
        /** @var History $history */
        $history = factory(History::class)->create();
        $player = factory(User::class)->create();

        $this->assertNull($history->players->first(fn (User $p) => $p->id === $player->id));

        $history->addPlayer($player);

        $history->refresh();
        $this->assertNotNull($history->players->first(fn (User $p) => $p->id === $player->id));
    }

    /** @test */
    public function cantAddSamePlayerTwice(): void
    {
        $this->expectException(UserIsAlreadyPlayerInHistory::class);

        $history = factory(History::class)->create();
        $player = factory(User::class)->create();
        $history->addPlayer($player);

        $history->addPlayer($player);
    }

    /** @test */
    public function deleteHistory(): void
    {
        $history = factory(History::class)->create();

        $response = $this->actingAs($history->owner)->delete(route('history.delete', $history));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('histories', [
            'id' => $history->id,
        ]);
    }

    /** @test */
    public function onlyOwnerCanDeleteHistory(): void
    {
        $baddie = factory(User::class)->create();
        $history = factory(History::class)->create();

        $response = $this->actingAs($baddie)->delete(route('history.delete', $history));

        $response->assertForbidden();
    }
}
