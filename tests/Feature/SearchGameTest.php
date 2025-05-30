<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature;

use App\Lfg;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\AuthenticatedRoutesTest;
use Tests\TestCase;

/**
 * @internal
 */
final class SearchGameTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoutesTest;

    public function testItReturnsAnEmptyResponseIfNoGamesExist(): void
    {
        $this
            ->login()
            ->get(route('lfg.index'))
            ->assertInertia(static fn (AssertableInertia $page) => $page->where('games', []));
    }

    public function testItReturnsAllGamesForAnUnfilteredRequest(): void
    {
        Lfg::factory()->count(3)->create();

        $this
            ->login()
            ->get(route('lfg.index'))
            ->assertInertia(
                static fn (AssertableInertia $page) => $page->has('games', 3),
            );
    }

    public function testItShowsOnlyGamesWithOpenSlots(): void
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
                static fn (AssertableInertia $page) => $page->has('games', 1)
                    ->where('games.0.id', $gameWithAvailableSlots->id),
            );
    }

    public function testItOnlyShowsGamesAfterTheSelectedStartDate(): void
    {
        $this->travelTo(now());

        Lfg::factory()->create([
            'start_date' => now(),
        ]);

        $this->login()
            ->get(route('lfg.index', ['start_date' => now()->subDay()->toIso8601String()]))
            ->assertInertia(static fn (AssertableInertia $page) => $page->has('games', 1));

        $this->login()
            ->get(route('lfg.index', ['start_date' => now()->addDay()->toIso8601String()]))
            ->assertInertia(static fn (AssertableInertia $page) => $page->has('games', 0));
    }

    public function testStartDateNeedsToBeAValidDate(): void
    {
        $this->login()
            ->get(route('lfg.index', ['start_date' => '::not-a-valid-date::']))
            ->assertSessionHasErrors('start_date');
    }

    public function testItDoesNotShowGamesThatLieInThePast(): void
    {
        Lfg::factory()->past()->create();

        $this->login()
            ->get(route('lfg.index'))
            ->assertInertia(static fn (AssertableInertia $page) => $page->has('games', 0));
    }

    public static function authenticatedRoutesProvider(): \Generator
    {
        yield from [
            'index' => ['get', '/lfg'],
        ];
    }
}
