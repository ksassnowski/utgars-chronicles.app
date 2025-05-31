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

namespace Tests\Feature\Models;

use App\Event;
use App\History;
use App\Period;
use Tests\TestCase;

/**
 * @internal
 */
final class EventTest extends TestCase
{
    private History $history;

    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        $this->history = History::factory()->create();
        $this->period = Period::factory()
            ->for($this->history)
            ->create();
    }

    public function testDeletingAnEventReordersTheRemainingEvents(): void
    {
        $event1 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create(['position' => 1]);
        $event2 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create(['position' => 2]);
        $event3 = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create(['position' => 3]);

        $event2->delete();

        self::assertEquals(1, $event1->fresh()->position);
        self::assertEquals(2, $event3->fresh()->position);
    }

    public function testDeletingAnEventDoesNotReorderRemainingEventsIfThereAreStillOtherEventsInTheSameGroup(): void
    {
        $originalEvent = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'position' => 1,
                'echo_group' => 0,
                'echo_group_position' => 1,
            ]);
        $intervention = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->intervention()
            ->create([
                'position' => 1,
                'echo_group' => 0,
                'echo_group_position' => 2,
            ]);
        $echo = Event::factory()
            ->for($originalEvent, 'cause')
            ->for($this->history)
            ->for($this->period)
            ->create([
                'position' => 1,
                'echo_group' => 0,
                'echo_group_position' => 3,
            ]);
        $otherEvent = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'position' => 2,
            ]);

        $echo->delete();
        self::assertEquals(2, $otherEvent->fresh()->position);

        $intervention->delete();
        self::assertEquals(2, $otherEvent->fresh()->position);

        $originalEvent->delete();
        self::assertEquals(1, $otherEvent->fresh()->position);
    }

    public function testDeletingEventFromEchoGroupReordersRemainingEventsInGroup(): void
    {
        $originalEvent = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'position' => 1,
                'echo_group' => 0,
                'echo_group_position' => 1,
            ]);
        $intervention = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->intervention()
            ->create([
                'position' => 1,
                'echo_group' => 0,
                'echo_group_position' => 2,
            ]);
        $echo = Event::factory()
            ->for($this->history)
            ->for($this->period)
            ->create([
                'position' => 1,
                'echo_group' => 0,
                'echo_group_position' => 3,
            ]);

        $intervention->delete();
        self::assertSame(2, $echo->fresh()->echo_group_position);
    }
}
