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

final class DatabaseEchoGroupRepository implements EchoGroupRepository
{
    public function getEchoGroup(Event $event): int
    {
        if (null !== $event->echo_group) {
            return $event->echo_group;
        }

        $largestGroup = Event::query()
            ->selectRaw('MAX(echo_group) + 1 as next_group')
            ->where('history_id', $event->history_id)
            ->value('next_group');

        /** @phpstan-ignore-next-line */
        return (int) $largestGroup;
    }

    public function getNextPosition(int $echoGroup): int
    {
        $nextPosition = Event::query()
            ->selectRaw('MAX(echo_group_position) + 1 as next_position')
            ->where('echo_group', $echoGroup)
            ->value('next_position');

        /** @phpstan-ignore-next-line */
        return (int) $nextPosition;
    }
}
