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

namespace Tests\Feature\MicroscopeEcho\Repository;

use App\Event;
use App\History;
use App\MicroscopeEcho\Repository\EchoGroupRepository;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait EchoGroupRepositoryContractTests
{
    public function testReturnEventEchoGroupIfItIsAlreadySet(): void
    {
        $event = Event::factory()->create(['echo_group' => 5]);
        $repository = $this->getRepository($event);

        $this->assertSame(5, $repository->getEchoGroup($event));
    }

    public function testReturnFirstEchoGroupIfNoGroupsExistsYet(): void
    {
        $event = Event::factory()->create(['echo_group' => null]);
        $repository = $this->getRepository($event);

        $this->assertSame(0, $repository->getEchoGroup($event));
    }

    public function testReturnNextEchoGroup(): void
    {
        $history = History::factory()->create();
        $events = Event::factory()
            ->for($history)
            ->count(3)
            ->sequence(
                ['echo_group' => 1],
                ['echo_group' => 2],
                ['echo_group' => 3],
            )
            ->create();
        $event = Event::factory()
            ->for($history)
            ->create(['echo_group' => null]);
        $repository = $this->getRepository(...$events);

        $this->assertSame(4, $repository->getEchoGroup($event));
    }

    public function testReturnNextEchoGroupPosition(): void
    {
        $history = History::factory()->create();
        $events = Event::factory()
            ->for($history)
            ->count(3)
            ->sequence(
                ['echo_group' => 1, 'echo_group_position' => 1],
                ['echo_group' => 1, 'echo_group_position' => 2],
                ['echo_group' => 2, 'echo_group_position' => 1],
            )
            ->create();
        $repository = $this->getRepository(...$events);

        $this->assertSame(3, $repository->getNextPosition(1));
        $this->assertSame(2, $repository->getNextPosition(2));
    }

    abstract protected function getRepository(Event ...$events): EchoGroupRepository;
}
