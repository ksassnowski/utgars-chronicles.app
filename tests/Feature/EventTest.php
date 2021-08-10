<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\Event;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use App\Events\BoardUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;

class EventTest extends TestCase
{
    use RefreshDatabase, ScopedRouteTest, GameRouteTest;

    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake([
            BoardUpdated::class,
        ]);

        $this->period = Period::factory()->create();
        $this->user = $this->period->history->owner;
    }

    /** @test */
    public function createEvent(): void
    {
        $response = $this->login()->postJson(
            route('periods.events.store', [$this->period->history, $this->period]),
            [
                'name' => '::event-name::',
                'type' => Type::DARK,
                'position' => 1,
            ],
        );

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('events', [
            'name' => '::event-name::',
            'type' => Type::DARK,
            'position' => 1,
            'period_id' => $this->period->id,
            'history_id' => $this->period->history->id,
        ]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validationTest(string $attribute, $value)
    {
        $payload = array_merge([
            'name' => '::event-name::',
            'type' => Type::DARK,
        ], [$attribute => $value]);

        $response = $this->login()->postJson(
            route('periods.events.store', [$this->period->history, $this->period]),
            $payload
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    public function validationProvider(): Generator
    {
        yield from [
            'no name' => ['name', ''],
            'incorrect type' => ['type', 'something-else'],
        ];
    }

    /** @test */
    public function updateEvent(): void
    {
        $event = Event::factory()->create([
            'history_id' => $this->period->history_id,
            'period_id' => $this->period->id,
            'name' => '::old-name::',
            'type' => Type::LIGHT,
        ]);

        $response = $this->login()->putJson(
            route('events.update', [$this->period->history, $event]),
            [
                'name' => '::new-name::',
                'type' => Type::DARK,
            ]
        );

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $event->refresh();
        $this->assertEquals('::new-name::', $event->name);
        $this->assertEquals(Type::DARK, $event->type);

        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /**
     * @test
     * @dataProvider validationProvider
     */
    public function validateAttributesOnUpdate(string $attribute, $value)
    {
        $payload = array_merge([
            'name' => '::new-name::',
            'type' => Type::DARK,
        ], [$attribute => $value]);

        $event = Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'name' => '::old-name::',
            'type' => Type::LIGHT,
        ]);

        $response = $this->login()->putJson(
            route('events.update', [$this->period->history, $event]),
            $payload
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    /** @test */
    public function deleteEvent(): void
    {
        $event = Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
        ]);

        $response = $this->login()
            ->delete(route('events.delete', [$this->period->history, $event]));

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /** @test */
    public function deletingAnEventReordersTheRemainingEvents(): void
    {
        $event1 = Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'position' => 1
        ]);
        $event2 = Event::factory()->create([
            'period_id' => $this->period->id,
            'position' => 2,
            'history_id' => $this->period->history_id,
        ]);
        $event3 = Event::factory()->create([
            'period_id' => $this->period->id,
            'position' => 3,
            'history_id' => $this->period->history_id,
        ]);

        $this->login()->delete(route('events.delete', [$this->period->history, $event2]));

        $this->assertEquals(1, $event1->refresh()->position);
        $this->assertEquals(2, $event3->refresh()->position);
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'create event' => [
                'post',
                fn () => Period::factory()->create(),
                fn (History $history, Period $period) => route('periods.events.store', [$history, $period]),
            ],
            'update event' => [
                'put',
                fn () => Event::factory()->create(),
                fn (History $history, Event $event) => route('events.update', [$history, $event]),
            ],
            'delete event' => [
                'delete',
                fn () => Event::factory()->create(),
                fn (History $history, Event $event) => route('events.delete', [$history, $event]),
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['periods.events.store'];
        yield ['events.update'];
        yield ['events.delete'];
        yield ['events.move'];
    }
}
