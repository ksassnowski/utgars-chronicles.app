<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Tests\Feature;

use App\CardType;
use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\Period;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\TestCase;

/**
 * @internal
 */
final class EventTest extends TestCase
{
    use GameRouteTest;
    use RefreshDatabase;
    use ScopedRouteTest;

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

    public function testCreateEvent(): void
    {
        $response = $this->login()->postJson(
            route('periods.events.store', [$this->period->history, $this->period]),
            [
                'name' => '::event-name::',
                'type' => CardType::Dark,
                'position' => 1,
            ],
        );

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('events', [
            'name' => '::event-name::',
            'type' => CardType::Dark,
            'position' => 1,
            'period_id' => $this->period->id,
            'history_id' => $this->period->history->id,
        ]);
        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /**
     * @dataProvider validationProvider
     *
     * @param mixed $value
     */
    public function testValidationTest(string $attribute, $value): void
    {
        $payload = \array_merge([
            'name' => '::event-name::',
            'type' => CardType::Dark,
        ], [$attribute => $value]);

        $response = $this->login()->postJson(
            route('periods.events.store', [$this->period->history, $this->period]),
            $payload,
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    public static function validationProvider(): iterable
    {
        yield from [
            'no name' => ['name', ''],
            'incorrect type' => ['type', 'something-else'],
        ];
    }

    public function testUpdateEvent(): void
    {
        $event = Event::factory()->create([
            'history_id' => $this->period->history_id,
            'period_id' => $this->period->id,
            'name' => '::old-name::',
            'type' => CardType::Light,
        ]);

        $response = $this->login()->putJson(
            route('events.update', [$this->period->history, $event]),
            [
                'name' => '::new-name::',
                'type' => CardType::Dark,
            ],
        );

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $event->refresh();
        self::assertEquals('::new-name::', $event->name);
        self::assertEquals(CardType::Dark, $event->type);

        EventFacade::assertDispatched(BoardUpdated::class);
    }

    /**
     * @dataProvider validationProvider
     *
     * @param mixed $value
     */
    public function testValidateAttributesOnUpdate(string $attribute, $value): void
    {
        $payload = \array_merge([
            'name' => '::new-name::',
            'type' => CardType::Dark,
        ], [$attribute => $value]);

        $event = Event::factory()->create([
            'period_id' => $this->period->id,
            'history_id' => $this->period->history_id,
            'name' => '::old-name::',
            'type' => CardType::Light,
        ]);

        $response = $this->login()->putJson(
            route('events.update', [$this->period->history, $event]),
            $payload,
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($attribute);
    }

    public function testDeleteEvent(): void
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

    public static function scopedRouteProvider(): \Generator
    {
        yield from [
            'create event' => [
                'post',
                static fn () => Period::factory()->create(),
                static fn (History $history, Period $period) => route('periods.events.store', [$history, $period]),
            ],
            'update event' => [
                'put',
                static fn () => Event::factory()->create(),
                static fn (History $history, Event $event) => route('events.update', [$history, $event]),
            ],
            'delete event' => [
                'delete',
                static fn () => Event::factory()->create(),
                static fn (History $history, Event $event) => route('events.delete', [$history, $event]),
            ],
        ];
    }

    public static function gameRouteProvider(): \Generator
    {
        yield ['periods.events.store'];

        yield ['events.update'];

        yield ['events.delete'];

        yield ['events.move'];
    }
}
