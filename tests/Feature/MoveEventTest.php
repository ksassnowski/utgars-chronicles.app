<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\Event;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use Tests\ScopedRouteTest;
use App\Events\BoardUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;

class MoveEventTest extends TestCase
{
    use RefreshDatabase, ScopedRouteTest;

    private History $history;
    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake();

        $this->user = User::factory()->create();
        $this->history = History::factory()->create(['owner_id' => $this->user->id]);
        $this->period = Period::factory()->create(['history_id' => $this->history->id]);
    }

    /** @test */
    public function broadcastEventAfterEventWasMoved(): void
    {
        Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'position' => 2
        ]);
        $event = Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'position' => 1
        ]);

        $this->login()->postJson(
            route('events.move', [$this->period->history, $event]),
            ['position' => 2]
        );

        EventFacade::assertDispatched(BoardUpdated::class);
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'move event' => [
                'post',
                fn () => Event::factory()->create(),
                fn (History $history, Event $event) => route('events.move', [$history, $event])
            ]
        ];
    }
}
