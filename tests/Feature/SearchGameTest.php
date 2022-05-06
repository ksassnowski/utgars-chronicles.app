<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Lfg;
use App\User;
use Generator;
use Tests\TestCase;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

class SearchGameTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest;

    /** @test */
    public function it_returns_an_empty_response_if_no_games_exist(): void
    {
        $this
            ->login()
            ->get(route('lfg.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page->where('games', []));
    }

    /** @test */
    public function it_returns_all_games_for_an_unfiltered_request(): void
    {
        Lfg::factory()->count(3)->create();

        $this
            ->login()
            ->get(route('lfg.index'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page->has('games', 3)
            );
    }

    /** @test */
    public function it_shows_only_games_with_open_slots(): void
    {
        $gameWithAvailableSlots = Lfg::factory(['slots' => 3])
            ->has(User::factory()->count(2))
            ->create();
        $fullGame = Lfg::factory(['slots' => 2])
            ->has(User::factory()->count(2))
            ->create();

        $this->login()
            ->get(route('lfg.index'))
            ->assertInertia(
                fn (AssertableInertia $page) => $page->has('games', 1)
                    ->where('games.0.id', $gameWithAvailableSlots->id)
            );
    }

    /** @test */
    public function it_only_shows_games_after_the_selected_start_date(): void
    {
        $this->travelTo(now());

        Lfg::factory()->create([
            'start_date' => now(),
        ]);

        $this->login()
            ->get(route('lfg.index', ['start_date' => now()->subDay()->toIso8601String()]))
            ->assertInertia(fn (AssertableInertia $page) => $page->has('games', 1));

        $this->login()
            ->get(route('lfg.index', ['start_date' => now()->addDay()->toIso8601String()]))
            ->assertInertia(fn (AssertableInertia $page) => $page->has('games', 0));
    }

    /** @test */
    public function start_date_needs_to_be_a_valid_date(): void
    {
        $this->login()
            ->get(route('lfg.index', ['start_date' => '::not-a-valid-date::']))
            ->assertSessionHasErrors('start_date');
    }

    /** @test */
    public function it_does_not_show_games_that_lie_in_the_past(): void
    {
        Lfg::factory()->past()->create();

        $this->login()
            ->get(route('lfg.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page->has('games', 0));
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'index' => ['get', '/lfg'],
        ];
    }
}
