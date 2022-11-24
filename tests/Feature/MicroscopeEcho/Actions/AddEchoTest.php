<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature\MicroscopeEcho\Actions;

use App\CardType;
use App\Event;
use App\History;
use App\MicroscopeEcho\Actions\AddEcho;
use App\MicroscopeEcho\Repository\InMemoryEchoGroupRepository;
use App\Period;
use InvalidArgumentException;
use Tests\TestCase;

/**
 * @internal
 */
final class AddEchoTest extends TestCase
{
    public function testAddNewEchoAtSamePositionThanEvent(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 2]);
        $event = Event::factory()
            ->for($history)
            ->create([
                'echo_group' => 1,
                'position' => 3,
            ]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $echo = $action->handle($cause, $event, '::echo-name::', CardType::Dark);

        self::assertSame('::echo-name::', $echo->name);
        self::assertSame(3, $echo->position);
        self::assertSame(CardType::Dark, $echo->type);
        self::assertTrue($echo->history()->is($event->history));
        self::assertTrue($echo->period()->is($event->period));
    }

    public function testAssociateEchoWithCause(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 2]);
        $event = Event::factory()
            ->for($history)
            ->create(['echo_group' => 1]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $echo = $action->handle($cause, $event, '::echo-name::', CardType::Dark);

        self::assertTrue($echo->cause()->is($cause));
    }

    public function testAddEchoToSameGroupAsEvent(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 2]);
        $event = Event::factory()
            ->for($history)
            ->create(['echo_group' => 1]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $echo = $action->handle($cause, $event, '::echo-name::', CardType::Dark);

        self::assertSame(1, $echo->echo_group);
    }

    public function testAddEchoToNewGroupTogetherWithEvent(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 2]);
        $event = Event::factory()
            ->for($history)
            ->create(['echo_group' => null]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $echo = $action->handle($cause, $event, '::echo-name::', CardType::Dark);

        self::assertSame(3, $echo->echo_group);
        self::assertSame(3, $event->fresh()->echo_group);
    }

    public function testAssignEchoToNextEchoGroupPosition(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 2]);
        Event::factory()
            ->for($history)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 1,
            ]);
        $event = Event::factory()
            ->for($history)
            ->create([
                'echo_group' => 1,
                'echo_group_position' => 2,
            ]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $echo = $action->handle($cause, $event, '::echo-name::', CardType::Dark);

        self::assertSame(3, $echo->echo_group_position);
    }

    public function testThrowsExceptionWhenUsingRegularEventAsCauseForEcho(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->create();
        $event = Event::factory()
            ->for($history)
            ->create();
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot use regular event as cause for Echo');

        $action->handle($cause, $event, '::echo-name::', CardType::Dark);
    }

    public function testCannotAddEchoToAnEventThatHappenedAfterTheCause(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 1]);
        $event = Event::factory()
            ->for($history)
            ->create(['echo_group' => 2]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Echo cause needs to have happened after changed event');

        $action->handle($cause, $event, '::echo-name::', CardType::Dark);
    }

    public function testCannotAddEchoToAnEventThatHappenedBecauseOfTheSameCause(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 1]);
        $event = Event::factory()
            ->for($history)
            ->create(['echo_group' => 1]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Echo cause needs to have happened after changed event');

        $action->handle($cause, $event, '::echo-name::', CardType::Dark);
    }

    public function testCannotAddEchoIfCauseAndEventBelongToDifferentHistories(): void
    {
        $cause = Event::factory()
            ->intervention()
            ->create(['echo_group' => 2]);
        $event = Event::factory()
            ->create(['echo_group' => 1]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cause and event need to belong to the same history');

        $action->handle($cause, $event, '::echo-name::', CardType::Dark);
    }

    public function testCannotAddEchoToAnEarlierPeriodThanTheCausesPeriod(): void
    {
        $history = History::factory()->create();
        $earlierPeriod = Period::factory()
            ->for($history)
            ->create(['position' => 1]);
        $laterPeriod = Period::factory()
            ->for($history)
            ->create(['position' => 2]);
        $cause = Event::factory()
            ->intervention()
            ->for($history)
            ->for($laterPeriod)
            ->create(['echo_group' => 1]);
        $event = Event::factory()
            ->for($history)
            ->for($earlierPeriod)
            ->create();
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Echo needs to happen after its cause');

        $action->handle($cause, $event, '::echo-name::', CardType::Dark);
    }

    public function testCannotAddEchoToAnEventThatHappenedBeforeTheCauseInTheSamePeriod(): void
    {
        $history = History::factory()->create();
        $period = Period::factory()
            ->for($history)
            ->create();
        $cause = Event::factory()
            ->intervention()
            ->for($history)
            ->for($period)
            ->create([
                'echo_group' => 1,
                'position' => 2,
            ]);
        $event = Event::factory()
            ->for($history)
            ->for($period)
            ->create(['position' => 1]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Echo needs to happen after its cause');

        $action->handle($cause, $event, '::echo-name::', CardType::Dark);
    }

    public function testCanAddEchoOnTopOfRegularEvent(): void
    {
        $history = History::factory()->create();
        $cause = Event::factory()
            ->for($history)
            ->intervention()
            ->create(['echo_group' => 2]);
        $event = Event::factory()
            ->for($history)
            ->create([
                'echo_group' => null,
                'echo_group_position' => 1,
            ]);
        $action = new AddEcho(
            new InMemoryEchoGroupRepository([$cause, $event]),
        );

        $echo = $action->handle($cause, $event, '::echo-name::', CardType::Dark);

        $event->refresh();
        self::assertSame(1, $event->echo_group_position);
        self::assertSame(3, $event->echo_group);
        self::assertSame(2, $echo->echo_group_position);
        self::assertSame(3, $echo->echo_group);
    }
}
