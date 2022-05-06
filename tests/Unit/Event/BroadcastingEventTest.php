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

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

trait BroadcastingEventTest
{
    /**
     * @test
     */
    public function implementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ShouldBroadcastNow::class, $this->createEvent());
    }

    /**
     * @test
     */
    public function broadcastOnCorrectChannel(): void
    {
        $event = $this->createEvent();

        $this->assertEquals(
            new PresenceChannel($this->getChannelName($event)),
            $event->broadcastOn(),
        );
    }

    abstract protected function createEvent();

    abstract protected function getChannelName($event): string;
}
