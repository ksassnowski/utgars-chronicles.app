<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\Event;
use App\Period;
use App\History;
use Tests\TestCase;
use App\Events\BoardUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;

class MoveEventTest extends TestCase
{
    use RefreshDatabase;

    private History $history;
    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake();

        $this->user = factory(User::class)->create();
        $this->history = factory(History::class)->create(['owner_id' => $this->user->id]);
        $this->period = factory(Period::class)->create(['history_id' => $this->history->id]);
    }

    /** @test */
    public function broadcastEventAfterEventWasMoved(): void
    {
        factory(Event::class)->create([
            'period_id' => $this->period->id,
            'position' => 2
        ]);
        $event = factory(Event::class)->create([
            'period_id' => $this->period->id,
            'position' => 1
        ]);

        $this->login()->postJson(
            route('events.move', $event),
            ['position' => 2]
        );

        EventFacade::assertDispatched(BoardUpdated::class);
    }
}
