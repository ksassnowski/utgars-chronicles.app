<?php declare(strict_types=1);

namespace Tests\Unit\Event;

use App\Focus;
use App\Events\FocusUpdated;
use PHPUnit\Framework\TestCase;

class FocusUpdatedTest extends TestCase
{
    use BroadcastingEventTest;

    protected function createEvent()
    {
        return new FocusUpdated(
            new Focus(['name' => '::new-name::', 'history_id' => 123])
        );
    }

    protected function getChannelName($event): string
    {
        return 'history.' . $event->focus->history_id;
    }
}
