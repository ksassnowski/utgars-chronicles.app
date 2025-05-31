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

namespace App\MicroscopeEcho\Actions;

use App\CardType;
use App\Event;
use App\EventType;
use App\MicroscopeEcho\Repository\EchoGroupRepository;
use Illuminate\Support\Facades\DB;

final class AddIntervention implements AddsIntervention
{
    public function __construct(
        private readonly EchoGroupRepository $echoGroups,
    ) {
    }

    public function handle(Event $event, string $name, CardType $type): Event
    {
        if ($event->isIntervention()) {
            throw new \InvalidArgumentException(
                'Cannot add an intervention to another intervention',
            );
        }

        $group = $this->echoGroups->getEchoGroup($event);
        $event->echo_group = $group;

        if (null === $event->echo_group_position) {
            $event->echo_group_position = 1;
        }

        $groupPosition = $event->echo_group_position + 1;

        return DB::transaction(static function () use ($event, $group, $name, $type, $groupPosition) {
            if ($event->isDirty()) {
                $event->update();
            }

            return Event::create([
                'name' => $name,
                'type' => $type,
                'period_id' => $event->period_id,
                'event_type' => EventType::Intervention,
                'echo_group' => $group,
                'echo_group_position' => $groupPosition,
                'position' => $event->position,
                'history_id' => $event->history_id,
            ]);
        });
    }
}
