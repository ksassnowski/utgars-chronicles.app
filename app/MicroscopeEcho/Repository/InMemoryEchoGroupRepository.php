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

namespace App\MicroscopeEcho\Repository;

use App\Event;

final class InMemoryEchoGroupRepository implements EchoGroupRepository
{
    /**
     * @param array<int, Event> $events
     */
    public function __construct(private readonly array $events)
    {
    }

    public function getEchoGroup(Event $event): int
    {
        if (null !== $event->echo_group) {
            return $event->echo_group;
        }

        $groups = collect($this->events)
            ->map(static fn (Event $event): int|null => $event->echo_group)
            ->filter();

        return $groups->isNotEmpty()
            ? $groups->max() + 1
            : 0;
    }

    public function getNextPosition(int $echoGroup): int
    {
        /** @var int $largestPosition */
        $largestPosition = collect($this->events)
            ->where('echo_group', $echoGroup)
            ->max('echo_group_position');

        return $largestPosition + 1;
    }
}
