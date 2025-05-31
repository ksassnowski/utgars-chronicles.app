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

namespace Tests\Feature\MicroscopeEcho\Actions;

use App\CardType;
use App\Event;
use App\EventType;
use App\MicroscopeEcho\Actions\AddIntervention;
use App\MicroscopeEcho\Repository\InMemoryEchoGroupRepository;
use Tests\TestCase;

/**
 * @internal
 */
final class AddInterventionTest extends TestCase
{
    public function testInsertsInterventionAtSamePositionThanOriginalEvent(): void
    {
        $event = Event::factory()->create([
            'position' => 5,
        ]);
        $action = new AddIntervention(
            new InMemoryEchoGroupRepository([$event]),
        );

        $intervention = $action->handle($event, '::intervention-name::', CardType::Dark);

        self::assertSame(EventType::Intervention, $intervention->event_type);
        self::assertTrue($intervention->history->is($event->history));
        self::assertSame('::intervention-name::', $intervention->name);
        self::assertSame(CardType::Dark, $intervention->type);
    }

    public function testInterventionGetsPlacedInSameEchoGroupAsEvent(): void
    {
        $event = Event::factory()->create([
            'echo_group' => 2,
        ]);
        $action = new AddIntervention(
            new InMemoryEchoGroupRepository([$event]),
        );

        $intervention = $action->handle($event, '::intervention-name::', CardType::Dark);

        self::assertSame(2, $intervention->echo_group);
    }

    public function testAssignInterventionAndEventToSameEchoGroup(): void
    {
        $event = Event::factory()->create([
            'echo_group' => null,
        ]);
        $action = new AddIntervention(
            new InMemoryEchoGroupRepository([$event]),
        );

        $intervention = $action->handle($event, '::intervention-name::', CardType::Dark);

        self::assertSame(0, $event->fresh()->echo_group);
        self::assertSame(0, $intervention->echo_group);
    }

    public function testAssignInterventionToNextPosition(): void
    {
        $event = Event::factory()->create([
            'echo_group_position' => 1,
        ]);
        $action = new AddIntervention(
            new InMemoryEchoGroupRepository([$event]),
        );

        $intervention = $action->handle($event, '::intervention-name::', CardType::Dark);

        self::assertSame(2, $intervention->echo_group_position);
    }

    public function testThrowExceptionWhenTryingToAddInterventionToIntervention(): void
    {
        $intervention = Event::factory()->intervention()->create();
        $action = new AddIntervention(
            new InMemoryEchoGroupRepository([$intervention]),
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot add an intervention to another intervention');

        $action->handle($intervention, '::intervention-name::', CardType::Dark);
    }
}
