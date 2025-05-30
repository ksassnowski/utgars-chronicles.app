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

use App\CardType;
use App\Events\BoardUpdated;
use App\History;
use App\Http\Controllers\History\CreatePeriodController;
use App\Http\Controllers\Period\UpdatePeriodController;
use App\Http\Requests\History\CreatePeriodRequest;
use App\Http\Requests\History\UpdatePeriodRequest;
use App\Period;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;

/**
 * @internal
 */
final class PeriodTest extends TestCase
{
    use RefreshDatabase;
    use ValidateRoutesTest;
    use GameRouteTest;
    use ScopedRouteTest;

    private History $history;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([BoardUpdated::class]);

        $this->user = User::factory()->create();
        $this->history = History::factory()->create(['owner_id' => $this->user->id]);
    }

    public static function scopedRouteProvider(): \Generator
    {
        yield from [
            'update period' => [
                'put',
                static fn () => Period::factory()->create(),
                static fn (History $history, Period $period) => route('periods.update', [$history, $period]),
            ],
            'delete period' => [
                'delete',
                static fn () => Period::factory()->create(),
                static fn (History $history, Period $period) => route('periods.delete', [$history, $period]),
            ],
        ];
    }

    public function testCreatePeriodForHistory(): void
    {
        $response = $this->login()->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-name::',
            'type' => CardType::Light,
            'position' => 1,
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        self::assertTrue($this->history->periods->contains('name', '::period-name::'));
        Event::assertDispatched(BoardUpdated::class);
    }

    public function testCreatePeriodBetweenTwoPeriods(): void
    {
        Period::factory()->create([
            'name' => '::period-1::',
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        Period::factory()->create([
            'name' => '::period-2::',
            'history_id' => $this->history->id,
            'position' => 2,
        ]);

        $response = $this->login()->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-3::',
            'type' => CardType::Light,
            'position' => 2,
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('periods', [
            'name' => '::period-1::',
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $this->assertDatabaseHas('periods', [
            'name' => '::period-2::',
            'history_id' => $this->history->id,
            'position' => 3,
        ]);
        $this->assertDatabaseHas('periods', [
            'name' => '::period-3::',
            'history_id' => $this->history->id,
            'position' => 2,
        ]);
    }

    public function testUpdatePeriod(): void
    {
        /** @var Period $period */
        $period = Period::factory()->create([
            'history_id' => $this->history->id,
            'type' => CardType::Dark,
        ]);

        $response = $this->login()->putJson(route('periods.update', [$period->history, $period]), [
            'name' => '::new-period-name::',
            'type' => CardType::Light,
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $period->refresh();
        self::assertEquals($period->name, '::new-period-name::');
        self::assertEquals($period->type, CardType::Light);
        Event::assertDispatched(BoardUpdated::class);
    }

    public static function validationProvider(): \Generator
    {
        yield from [
            [
                CreatePeriodController::class,
                '__invoke',
                CreatePeriodRequest::class,
            ],
            [
                UpdatePeriodController::class,
                '__invoke',
                UpdatePeriodRequest::class,
            ],
        ];
    }

    public function testDeletePeriod(): void
    {
        /** @var Period $period */
        $period = Period::factory()->create([
            'history_id' => $this->history->id,
        ]);

        $response = $this->login()->delete(route('periods.delete', [$period->history, $period]));

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('periods', ['id' => $period->id]);
        Event::assertDispatched(BoardUpdated::class);
    }

    public function testDeletingPeriodReordersTheRemainingPeriods(): void
    {
        $period1 = Period::factory()->create(['history_id' => $this->history->id, 'position' => 1]);
        $period2 = Period::factory()->create(['history_id' => $this->history->id, 'position' => 2]);
        $period3 = Period::factory()->create(['history_id' => $this->history->id, 'position' => 3]);

        $this->login()->delete(route('periods.delete', [$this->history->id, $period2]));

        self::assertEquals(1, $period1->refresh()->position);
        self::assertEquals(2, $period3->refresh()->position);
    }

    public static function gameRouteProvider(): \Generator
    {
        yield ['history.periods.store'];

        yield ['periods.update'];

        yield ['periods.delete'];

        yield ['periods.move'];
    }
}
