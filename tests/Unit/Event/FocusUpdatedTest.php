<?php

declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Events\FocusUpdated;
use App\Focus;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FocusUpdatedTest extends TestCase
{
    use BroadcastingEventTest;

    protected function createEvent()
    {
        return new FocusUpdated(
            new Focus(['name' => '::new-name::', 'history_id' => 123]),
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.' . $event->focus->history_id;
    }
}
