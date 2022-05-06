<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Events\BoardUpdated;
use App\History;
use App\Period;
use App\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\ScopedRouteTest;
use Tests\TestCase;

/**
 * @internal
 */
final class MovePeriodTest extends TestCase
{
    use RefreshDatabase;
    use ScopedRouteTest;

    private History $history;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->history = History::factory()->create([
            'owner_id' => $this->user->id,
        ]);
    }

    public function testMovePeriodDownASlot(): void
    {
        $period1 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $period2 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);

        $period1->move(2);

        self::assertSame($period1->refresh()->position, 2);
        self::assertSame($period2->refresh()->position, 1);
    }

    public function testMovePeriodUpASlot(): void
    {
        $period1 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $period2 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);

        $period2->move(1);

        self::assertSame($period1->refresh()->position, 2);
        self::assertSame($period2->refresh()->position, 1);
    }

    public function testMovePeriodDownTwoSlots(): void
    {
        $period1 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $period2 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);
        $period3 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 3,
        ]);

        $period1->move(3);

        self::assertSame($period1->refresh()->position, 3);
        self::assertSame($period2->refresh()->position, 1);
        self::assertSame($period3->refresh()->position, 2);
    }

    public function testMoveUpTwoSlotsPeriod(): void
    {
        $period1 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $period2 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);
        $period3 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 3,
        ]);

        $period3->move(1);

        self::assertSame($period1->refresh()->position, 2);
        self::assertSame($period2->refresh()->position, 3);
        self::assertSame($period3->refresh()->position, 1);
    }

    public function testMovePeriodDownInBetweenTwoPeriods(): void
    {
        $period1 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $period2 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);
        $period3 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 3,
        ]);
        $period4 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 4,
        ]);

        $period2->move(3);

        self::assertSame($period1->refresh()->position, 1);
        self::assertSame($period2->refresh()->position, 3);
        self::assertSame($period3->refresh()->position, 2);
        self::assertSame($period4->refresh()->position, 4);
    }

    public function testMovePeriodUpInBetweenTwoPeriods(): void
    {
        $period1 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);
        $period2 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);
        $period3 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 3,
        ]);
        $period4 = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 4,
        ]);

        $period3->move(2);

        self::assertSame($period1->refresh()->position, 1);
        self::assertSame($period2->refresh()->position, 3);
        self::assertSame($period3->refresh()->position, 2);
        self::assertSame($period4->refresh()->position, 4);
    }

    public function testBroadcastEventAfterPeriodHasBeenMoved(): void
    {
        Event::fake();

        Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 2,
        ]);
        $period = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);

        $this->login()->postJson(route('periods.move', [$this->history, $period]), [
            'position' => 2,
        ]);

        Event::assertDispatched(BoardUpdated::class);
    }

    /**
     * @dataProvider invalidPositionProvider
     */
    public function testValidatePosition(array $payload): void
    {
        $period = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);

        $response = $this->login()->postJson(route('periods.move', [$this->history, $period]), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('position');
    }

    public function invalidPositionProvider(): Generator
    {
        yield from [
            'missing' => [[]],
            'negative' => [['position' => -1]],
            'zero' => [['position' => 0]],
            'non numeric' => [['position' => 'two']],
        ];
    }

    public function testCanOnlyMovePeriodsBelongingToOwnHistory(): void
    {
        $otherUser = User::factory()->create();
        $period = Period::factory()->create([
            'history_id' => $this->history->id,
            'position' => 1,
        ]);

        $response = $this->actingAs($otherUser)->postJson(route('periods.move', [$this->history, $period]), [
            'position' => 2,
        ]);

        $response->assertForbidden();
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'move period' => [
                'post',
                static fn () => Period::factory()->create(),
                static fn (History $history, Period $period) => route('periods.move', [$history, $period]),
            ],
        ];
    }
}
