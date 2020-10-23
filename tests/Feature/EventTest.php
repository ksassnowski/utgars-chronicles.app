<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\Event;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use Tests\ScopedRouteTest;
use App\Events\BoardUpdated;
use Tests\AuthorizeHistoryTest;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;

class EventTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, AuthorizeHistoryTest, ScopedRouteTest;

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

    public function authenticatedRoutesProvider()
    {
        yield from [
            'create event' => ['post', '/histories/1/periods/1/events'],
            'update event' => ['put', '/histories/1/events/1'],
            'delete event' => ['delete', '/histories/1/events/1'],
        ];
    }

    public function authorizationProvider(): Generator
    {
        yield from [
            'create event' => [
                ['name' => '::event-name::', 'type' => Type::DARK, 'position' => 1],
                fn (Period $period) => route('periods.events.store', [$period->history, $period]),
                'POST',
                201,
                fn (History $history) => Period::factory()->create(['history_id' => $history->id]),
            ],
            'edit event' => [
                ['name' => '::event-name::', 'type' => Type::DARK],
                fn (Event $event) => route('events.update', [$event->period->history, $event]),
                'put',
                200,
                function (History $history) {
                    $period = Period::factory()->create(['history_id' => $history->id]);
                    return Event::factory()->create(['period_id' => $period->id]);
                },
            ],
            'delete event' => [
                [],
                fn (Event $event) => route('events.delete', [$event->period->history, $event]),
                'delete',
                204,
                function (History $history) {
                    $period = Period::factory()->create(['history_id' => $history->id]);
                    return Event::factory()->create(['period_id' => $period->id]);
                },
            ]
        ];
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

        $response->assertStatus(201);
        $this->assertTrue(
            $this->period->events->contains('name', '::event-name::')
        );
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

        $response->assertOk();
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
        $event = Event::factory()->create(['period_id' => $this->period->id]);

        $response = $this->login()
            ->deleteJson(route('events.delete', [$this->period->history, $event]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /** @test */
    public function deletingAnEventReordersTheRemainingEvents(): void
    {
        $event1 = Event::factory()->create(['period_id' => $this->period->id, 'position' => 1]);
        $event2 = Event::factory()->create(['period_id' => $this->period->id, 'position' => 2]);
        $event3 = Event::factory()->create(['period_id' => $this->period->id, 'position' => 3]);

        $this->login()->deleteJson(route('events.delete', [$this->period->history, $event2]));

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
}
