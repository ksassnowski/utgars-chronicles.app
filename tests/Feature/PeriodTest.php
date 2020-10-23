<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\User;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use Tests\ScopedRouteTest;
use App\Events\BoardUpdated;
use Tests\ValidateRoutesTest;
use Tests\AuthorizeHistoryTest;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Http\Requests\History\CreatePeriodRequest;
use App\Http\Requests\History\UpdatePeriodRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Period\UpdatePeriodController;
use App\Http\Controllers\History\CreatePeriodController;

class PeriodTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, AuthorizeHistoryTest, ValidateRoutesTest, ScopedRouteTest;

    private History $history;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([BoardUpdated::class]);

        $this->user = User::factory()->create();
        $this->history = History::factory()->create(['owner_id' => $this->user->id]);
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'create period' => [
                'post',
                fn (History $history) => route('history.periods.store', $history),
                fn () => History::factory()->create(),
            ],
            'update period' => [
                'put',
                fn (Period $period) => route('periods.update', [$period->history, $period]),
                fn () => Period::factory()->create(),
            ],
            'delete period' => [
                'delete',
                fn (Period $period) => route('periods.delete', [$period->history, $period]),
                fn () => Period::factory()->create(),
            ],
        ];
    }

    public function authorizationProvider(): Generator
    {
        yield from [
            'create period' => [
                ['name' => '::period-name::', 'type' => Type::DARK, 'position' => 1],
                fn (History $history) => route('history.periods.store', $history),
                'post',
                201
            ],
            'update period' => [
                ['name' => '::new-period-name::'],
                fn (Period $period) => route('periods.update', [$period->history, $period]),
                'put',
                200,
                fn (History $history) => Period::factory()->create(['history_id' => $history->id]),
            ],
            'delete period' => [
                ['name' => '::new-period-name::'],
                fn (Period $period) => route('periods.delete', [$period->history, $period]),
                'delete',
                204,
                fn (History $history) => Period::factory()->create(['history_id' => $history->id]),
            ],
        ];
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update period' => [
                'put',
                fn () => Period::factory()->create(),
                fn (History $history, Period $period) => route('periods.update', [$history, $period]),
            ],
            'delete period' => [
                'delete',
                fn () => Period::factory()->create(),
                fn (History $history, Period $period) => route('periods.delete', [$history, $period]),
            ],
        ];
    }

    /** @test */
    public function createPeriodForHistory(): void
    {
        $response = $this->login()->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-name::',
            'type' => Type::LIGHT,
            'position' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertTrue($this->history->periods->contains('name', '::period-name::'));
        Event::assertDispatched(BoardUpdated::class);
    }

    /** @test */
    public function createPeriodBetweenTwoPeriods(): void
    {
        Period::factory()->create([
            'name' => '::period-1::',
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        Period::factory()->create([
            'name' => '::period-2::',
            'history_id' => $this->history->id,
            'position' => 2
        ]);

        $response = $this->login()->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-3::',
            'type' => Type::LIGHT,
            'position' => 2,
        ]);

        $response->assertStatus(201);
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

    /** @test */
    public function updatePeriod(): void
    {
        /** @var Period $period */
        $period = Period::factory()->create([
            'history_id' => $this->history->id,
            'type' => Type::DARK,
        ]);

        $response = $this->login()->putJson(route('periods.update', [$period->history, $period]), [
            'name' => '::new-period-name::',
            'type' => Type::LIGHT,
        ]);

        $response->assertOk();

        $period->refresh();
        $this->assertEquals($period->name, '::new-period-name::');
        $this->assertEquals($period->type, Type::LIGHT);
        Event::assertDispatched(BoardUpdated::class);
    }

    public function validationProvider(): Generator
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
            ]
        ];
    }

    /** @test */
    public function deletePeriod(): void
    {
        /** @var Period $period */
        $period = Period::factory()->create([
            'history_id' => $this->history->id
        ]);

        $response = $this->login()->deleteJson(route('periods.delete', [$period->history, $period]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('periods', ['id' => $period->id]);
        Event::assertDispatched(BoardUpdated::class);
    }

    /** @test */
    public function deletingPeriodReordersTheRemainingPeriods(): void
    {
        $period1 = Period::factory()->create(['history_id' => $this->history->id, 'position' => 1]);
        $period2 = Period::factory()->create(['history_id' => $this->history->id, 'position' => 2]);
        $period3 = Period::factory()->create(['history_id' => $this->history->id, 'position' => 3]);

        $this->login()->deleteJson(route('periods.delete', [$this->history->id, $period2]));

        $this->assertEquals(1, $period1->refresh()->position);
        $this->assertEquals(2, $period3->refresh()->position);
    }
}
