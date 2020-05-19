<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\Event;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use App\Events\BoardUpdated;
use Tests\AuthorizeHistoryTest;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;

class EventTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, AuthorizeHistoryTest;

    private Period $period;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake([
            BoardUpdated::class,
        ]);

        $this->period = factory(Period::class)->create();
        $this->user = $this->period->history->owner;
    }

    public function authenticatedRoutesProvider()
    {
        yield from [
            'create event' => ['post', '/periods/1/events'],
            'update event' => ['put', '/events/1'],
            'delete event' => ['delete', '/events/1'],
        ];
    }

    public function authorizationProvider(): Generator
    {
        yield from [
            'create event' => [
                ['name' => '::event-name::', 'type' => Type::DARK],
                '/periods/2/events',
                'post',
                201,
                fn (History $history) => factory(Period::class)->create(['history_id' => $history->id]),
            ],
            'edit event' => [
                ['name' => '::event-name::', 'type' => Type::DARK],
                '/events/1',
                'put',
                200,
                function (History $history) {
                    $period = factory(Period::class)->create(['history_id' => $history->id]);
                    factory(Event::class)->create(['period_id' => $period->id]);
                },
            ],
            'delete event' => [
                [],
                '/events/1',
                'delete',
                204,
                function (History $history) {
                    $period = factory(Period::class)->create(['history_id' => $history->id]);
                    factory(Event::class)->create(['period_id' => $period->id]);
                },
            ]
        ];
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
    public function deleteEvent(): void
    {
        $event = factory(Event::class)->create(['period_id' => $this->period->id]);

        $response = $this->login()
            ->deleteJson(route('events.delete', $event));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }
}
