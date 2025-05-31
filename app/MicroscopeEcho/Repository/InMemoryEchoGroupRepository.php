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
            ->map(static fn (Event $event): ?int => $event->echo_group)
            ->filter();

        return $groups->isNotEmpty()
            ? $groups->max() + 1
            : 0;
    }
}
