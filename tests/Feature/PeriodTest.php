<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\User;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use App\Events\BoardUpdated;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PeriodTest extends TestCase
{
    use RefreshDatabase;

    private History $history;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([BoardUpdated::class]);

        $this->user = factory(User::class)->create();
        $this->history = factory(History::class)->create(['owner_id' => $this->user->id]);
    }

    /**
     * @test
     * @dataProvider routeProvider
     */
    public function authenticateRoutesTest(string $method, string $url): void
    {
        $method = $method . 'Json';

        /** @var TestResponse $response */
        $response = $this->{$method}($url);

        $response->assertUnauthorized();
    }

    public function routeProvider()
    {
        yield from [
            'create period' => ['post', '/histories/1/periods'],
            'update period' => ['put', '/periods/1'],
            'delete period' => ['delete', 'periods/1'],
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
        factory(Period::class)->create([
            'name' => '::period-1::',
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        factory(Period::class)->create([
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

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validateAttributesOnCreate(string $attribute, $value): void
    {
        $payload = array_merge([
            'name' => '::name::',
            'type' => Type::LIGHT,
        ], [$attribute => $value]);

        $response = $this->login()->postJson(
            route('history.periods.store', $this->history),
            $payload
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    /** @test */
    public function needToBeLoggedInToCreatePeriod(): void
    {
        $response = $this->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-name::',
        ]);

        $response->assertUnauthorized();
    }

    /** @test */
    public function canOnlyCreatePeriodForOwnHistories(): void
    {
        $otherUser = factory(User::class)->create();

        $response = $this->actingAs($otherUser)->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-name::',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function canCreatePeriodForHistoryThatIAmAPlayerOf(): void
    {
        $player = factory(User::class)->create();
        $this->history->addPlayer($player);

        $response = $this->actingAs($player)->postJson(route('history.periods.store', $this->history), [
            'name' => '::period-name::',
            'type' => Type::DARK,
            'position' => 1,
        ]);

        $response->assertStatus(201);
        $this->history->periods->contains('name', '::period-name::');
    }

    /** @test */
    public function updatePeriod(): void
    {
        /** @var Period $period */
        $period = factory(Period::class)->create([
            'history_id' => $this->history->id,
            'type' => Type::DARK,
        ]);

        $response = $this->login()->putJson(route('periods.update', $period), [
            'name' => '::new-period-name::',
            'type' => Type::LIGHT,
        ]);

        $response->assertOk();

        $period->refresh();
        $this->assertEquals($period->name, '::new-period-name::');
        $this->assertEquals($period->type, Type::LIGHT);
        Event::assertDispatched(BoardUpdated::class);
    }

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validateAttributes(string $attribute, $value): void
    {
        /** @var Period $period */
        $period = factory(Period::class)->create([
            'history_id' => $this->history->id
        ]);

        $payload = array_merge([
            'name' => '::name::',
            'type' => Type::LIGHT,
        ], [$attribute => $value]);

        $response = $this->login()->putJson(
            route('periods.update', $period),
            $payload
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    public function validationProvider(): Generator
    {
        yield from [
            'empty name' => ['name', ''],
            'incorrect type' => ['type', 'something-else'],
        ];
    }

    /** @test */
    public function canOnlyUpdatePeriodsBelongingToOwnHistory(): void
    {
        $otherUser = factory(User::class)->create();
        /** @var Period $period */
        $period = factory(Period::class)->create([
            'history_id' => $this->history->id
        ]);

        $response = $this->actingAs($otherUser)->putJson(route('periods.update', $period), [
            'name' => '::new-period-name::',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function deletePeriod(): void
    {
        /** @var Period $period */
        $period = factory(Period::class)->create([
            'history_id' => $this->history->id
        ]);

        $response = $this->login()->deleteJson(route('periods.delete', $period));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('periods', ['id' => $period->id]);
        Event::assertDispatched(BoardUpdated::class);
    }

    /** @test */
    public function needToBeLoggedInToDeletePeriod()
    {
        $period = factory(Period::class)->create([
            'history_id' => $this->history->id
        ]);

        $response = $this->deleteJson(route('periods.delete', $period));

        $response->assertUnauthorized();
    }

    /** @test */
    public function canOnlyDeletePeriodsBelongingToOwnHistory(): void
    {
        $otherUser = factory(User::class)->create();
        $period = factory(Period::class)->create([
            'history_id' => $this->history->id
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->deleteJson(route('periods.delete', $period));

        $response->assertForbidden();
    }

    /** @test */
    public function deletingPeriodReordersTheRemainingPeriods(): void
    {
        $period1 = factory(Period::class)->create(['history_id' => $this->history->id, 'position' => 1]);
        $period2 = factory(Period::class)->create(['history_id' => $this->history->id, 'position' => 2]);
        $period3 = factory(Period::class)->create(['history_id' => $this->history->id, 'position' => 3]);

        $this->login()->deleteJson(route('periods.delete', $period2));

        $this->assertEquals(1, $period1->refresh()->position);
        $this->assertEquals(2, $period3->refresh()->position);
    }
}
