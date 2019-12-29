<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\User;
use App\Event;
use Generator;
use App\Period;
use Tests\TestCase;
use App\Events\EventCreated;
use App\Events\EventDeleted;
use App\Events\EventUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake([
            EventCreated::class,
            EventUpdated::class,
            EventDeleted::class,
        ]);

        $this->period = factory(Period::class)->create();
        $this->user = $this->period->history->owner;
    }

    /**
     * @test
     * @dataProvider routeProvider
     */
    public function authenticatedRoutes(string $method, string $url): void
    {
        $method = $method . 'Json';
        $response = $this->{$method}($url);

        $response->assertUnauthorized();
    }

    public function routeProvider()
    {
        yield from [
            'create event' => ['post', '/periods/1/events'],
            'update event' => ['put', '/events/1'],
            'delete event' => ['delete', '/events/1'],
        ];
    }

    /** @test */
    public function creatingAnEventNeedsAuthorization(): void
    {
        $response = $this->postJson(
            route('periods.events.store', $this->period),
            [
                'name' => '::event-name::',
                'type' => Type::DARK,
            ],
        );

        $response->assertUnauthorized();
    }

    /** @test */
    public function canOnlyCreateEventForOwnHistories()
    {
        $otherUser = factory(User::class)->create();
        $response = $this->actingAs($otherUser)->postJson(
            route('periods.events.store', $this->period),
            [
                'name' => '::event-name::',
                'type' => Type::DARK,
            ],
        );

        $response->assertForbidden();
    }

    /** @test */
    public function createEvent(): void
    {
        $response = $this->login()->postJson(
            route('periods.events.store', $this->period),
            [
                'name' => '::event-name::',
                'type' => Type::DARK,
            ],
        );

        $response->assertStatus(201);
        $this->assertTrue(
            $this->period->events->contains('name', '::event-name::')
        );
        EventFacade::assertDispatched(EventCreated::class);
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
            route('periods.events.store', $this->period),
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
        $event = factory(Event::class)->create([
            'period_id' => $this->period->id,
            'name' => '::old-name::',
            'type' => Type::LIGHT,
        ]);

        $response = $this->login()->putJson(
            route('events.update', $event),
            [
                'name' => '::new-name::',
                'type' => Type::DARK,
            ]
        );

        $response->assertOk();
        $event->refresh();
        $this->assertEquals('::new-name::', $event->name);
        $this->assertEquals(Type::DARK, $event->type);

        EventFacade::assertDispatched(
            EventUpdated::class,
            fn (EventUpdated $e) => $e->event->id === $event->id
        );
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

        $event = factory(Event::class)->create([
            'period_id' => $this->period->id,
            'name' => '::old-name::',
            'type' => Type::LIGHT,
        ]);

        $response = $this->login()->putJson(
            route('events.update', $event),
            $payload
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    /** @test */
    public function canOnlyUpdateEventsThatBelongToOwnHistory()
    {
        $otherUser = factory(User::class)->create();
        $event = factory(Event::class)->create(['period_id' => $this->period->id]);

        $response = $this->actingAs($otherUser)->putJson(
            route('events.update', $event),
            []
        );

        $response->assertForbidden();
    }

    /** @test */
    public function deleteEvent(): void
    {
        $event = factory(Event::class)->create(['period_id' => $this->period->id]);

        $response = $this->login()
            ->deleteJson(route('events.delete', $event));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        EventFacade::assertDispatched(
            EventDeleted::class,
            fn (EventDeleted $e) => $e->id === $event->id && $e->period->id === $event->period->id
        );
    }

    /** @test */
    public function canOnlyDeleteEventsThatBelongToOwnHistory(): void
    {
        $otherUser = factory(User::class)->create();
        $event = factory(Event::class)->create(['period_id' => $this->period->id]);

        $response = $this->actingAs($otherUser)
            ->deleteJson(route('events.delete', $event));

        $response->assertForbidden();
    }
}
