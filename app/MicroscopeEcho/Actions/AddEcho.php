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

namespace App\MicroscopeEcho\Actions;

use App\CardType;
use App\Event;
use App\EventType;
use App\MicroscopeEcho\Repository\EchoGroupRepository;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class AddEcho implements AddsEcho
{
    public function __construct(
        private readonly EchoGroupRepository $echoGroups,
    ) {
    }

    public function handle(
        Event $cause,
        Event $event,
        string $name,
        CardType $type,
    ): Event {
        if ($cause->history_id !== $event->history_id) {
            throw new InvalidArgumentException(
                'Cause and event need to belong to the same history',
            );
        }

        if ($cause->isRegularEvent()) {
            throw new InvalidArgumentException('Cannot use regular event as cause for Echo');
        }

        if ($cause->echo_group <= $event->echo_group) {
            throw new InvalidArgumentException(
                'Echo cause needs to have happened after changed event',
            );
        }

        $group = $this->echoGroups->getEchoGroup($event);
        $position = $this->echoGroups->getNextPosition($group);

        return DB::transaction(static function () use ($cause, $event, $name, $type, $position, $group): Event {
            if (null === $event->echo_group) {
                $event->update(['echo_group' => $group]);
            }

            return Event::create([
                'name' => $name,
                'type' => $type,
                'position' => $event->position,
                'history_id' => $cause->history_id,
                'period_id' => $event->period_id,
                'event_id' => $cause->id,
                'event_type' => EventType::Echo,
                'echo_group' => $group,
                'echo_group_position' => $position,
            ]);
        });
    }
}
