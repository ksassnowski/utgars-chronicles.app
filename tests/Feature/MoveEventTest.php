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

use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Period;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;
use Tests\ScopedRouteTest;
use Tests\TestCase;

/**
 * @internal
 */
final class MoveEventTest extends TestCase
{
    use RefreshDatabase;
    use ScopedRouteTest;

    private History $history;

    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake();

        $this->user = User::factory()->create();
        $this->history = History::factory()->create(['owner_id' => $this->user->id]);
        $this->period = Period::factory()->create(['history_id' => $this->history->id]);
    }

    public function testBroadcastEventAfterEventWasMoved(): void
    {
        Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'position' => 2,
        ]);
        $event = Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'position' => 1,
        ]);

        $this->login()->postJson(
            route('events.move', [$this->period->history, $event]),
            ['position' => 2],
        );

        EventFacade::assertDispatched(BoardUpdated::class);
    }

    public function testMovingEventMovesAllEventsInSameEchoGroupToSameLocation(): void
    {
        $event = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 3,
                'position' => 1,
            ]);
        $event2 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 2,
                'position' => 1,
            ]);
        $event3 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 1,
                'position' => 1,
            ]);

        $this
            ->login()
            ->postJson(
                route('events.move', [$this->history, $event]),
                ['position' => 2],
            )
            ->assertSessionHasNoErrors();

        self::assertSame(2, $event2->fresh()->position);
        self::assertSame(2, $event3->fresh()->position);
    }

    public function testDontMoveEventsThatAreNotPartOfAnEchoGroup(): void
    {
        $event = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => null,
                'position' => 1,
            ]);
        $event2 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => null,
                'position' => 2,
            ]);
        $event3 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => null,
                'position' => 3,
            ]);

        $this
            ->login()
            ->postJson(
                route('events.move', [$this->history, $event]),
                ['position' => 2],
            )
            ->assertSessionHasNoErrors();

        self::assertSame(2, $event->fresh()->position);
        self::assertSame(1, $event2->fresh()->position);
        self::assertSame(3, $event3->fresh()->position);
    }

    public function testDontMoveEventsInSameEchoGroupButDifferentGame(): void
    {
        $history = History::factory()->create();
        $event = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => 1,
                'position' => 1,
            ]);
        $eventInDifferentGame = Event::factory()
            ->for($history)
            ->create([
                'echo_group' => 1,
                'position' => 1,
            ]);

        $this
            ->login()
            ->postJson(
                route('events.move', [$this->history, $event]),
                ['position' => 2],
            )
            ->assertSessionHasNoErrors();

        self::assertSame(1, $eventInDifferentGame->fresh()->position);
    }

    public function testCanMoveEchoStackIntoFirstPosition(): void
    {
        $echoEvent1 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 2,
                'position' => 2,
            ]);
        $echoEvent2 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 1,
                'position' => 2,
            ]);
        $event = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'echo_group' => null,
                'position' => 1,
            ]);

        $this
            ->login()
            ->postJson(
                route('events.move', [$this->history, $echoEvent1]),
                ['position' => 1],
            )
            ->assertSessionHasNoErrors();

        self::assertSame(2, $event->fresh()->position);
        self::assertSame(1, $echoEvent1->fresh()->position);
        self::assertSame(1, $echoEvent2->fresh()->position);
    }

    public static function scopedRouteProvider(): \Generator
    {
        yield from [
            'move event' => [
                'post',
                static fn () => Event::factory()->create(),
                static fn (History $history, Event $event) => route('events.move', [$history, $event]),
            ],
        ];
    }
}
