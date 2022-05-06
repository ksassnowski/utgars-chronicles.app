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

namespace Tests\Unit\Event;

use App\Events\FocusDefined;
use App\Focus;
use App\History;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FocusDefinedTest extends TestCase
{
    public function testBroadCastWithCorrectData(): void
    {
        $history = new History();
        $focus = new Focus(['name' => '::focus-name::']);
        $focus->id = 999;
        $history->setRelation('focus', collect($focus));

        $event = new FocusDefined($history, $focus);

        self::assertEquals([
            'id' => 999,
            'name' => '::focus-name::',
        ], $event->broadcastWith());
    }

    public function testBroadcastOnCorrectChannel(): void
    {
        $history = new History(['id' => 1]);

        $event = new FocusDefined($history, new Focus());

        self::assertEquals(new PresenceChannel('history.1'), $event->broadcastOn());
    }

    public function testEventShouldBroadcast(): void
    {
        $event = new FocusDefined(new History(), new Focus());

        self::assertInstanceOf(ShouldBroadcast::class, $event);
    }
}
