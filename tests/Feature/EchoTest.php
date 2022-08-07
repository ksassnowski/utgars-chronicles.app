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

namespace Tests\Feature;

use App\CardType;
use App\Event;
use App\Events\BoardUpdated;
use App\History;
use App\MicroscopeEcho\Actions\AddsEcho;
use App\MicroscopeEcho\Actions\AddsIntervention;
use Generator;
use Illuminate\Support\Facades\Event as EventFacade;
use Mockery as m;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\TestCase;

/**
 * @internal
 */
final class EchoTest extends TestCase
{
    use GameRouteTest;
    use ScopedRouteTest;

    private History $history;

    protected function setUp(): void
    {
        parent::setUp();

        EventFacade::fake([BoardUpdated::class]);

        $this->history = History::factory()->create();
    }

    public function testAddInterventionToEvent(): void
    {
        $actionMock = $this->spy(AddsIntervention::class);
        $event = Event::factory()
            ->for($this->history)
            ->create();

        $this->actingAs($this->history->owner)
            ->post(
                route('events.interventions.store', [$this->history, $event]),
                [
                    'name' => '::name::',
                    'type' => CardType::Dark->value,
                ],
            )
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $actionMock
            ->shouldHaveReceived('handle')
            ->once()
            ->with(
                m::on(static fn (Event $actualEvent) => $actualEvent->is($event)),
                '::name::',
                CardType::Dark,
            );
        EventFacade::assertDispatched(
            BoardUpdated::class,
            fn (BoardUpdated $event): bool => $event->history->is($this->history),
        );
    }

    /**
     * @dataProvider invalidInterventionPayloadProvider
     */
    public function testInterventionValidation(array $payload, string $expectedKey): void
    {
        $event = Event::factory()
            ->for($this->history)
            ->create();

        $this->actingAs($this->history->owner)
            ->post(
                route('events.interventions.store', [$this->history, $event]),
                $payload,
            )
            ->assertRedirect()
            ->assertSessionHasErrors($expectedKey);
    }

    public function invalidInterventionPayloadProvider(): Generator
    {
        yield from [
            'missing name' => [
                ['type' => CardType::Dark->value],
                'name',
            ],
            'name too long' => [
                ['name' => \str_repeat('a', 256), 'type' => CardType::Dark->value],
                'name',
            ],
            'type missing' => [
                ['name' => '::name::'],
                'type',
            ],
            'type not a valid enum' => [
                ['name' => '::name::', 'type' => '::invalid::'],
                'type',
            ],
        ];
    }

    public function testAddEchoToEvent(): void
    {
        $actionMock = $this->spy(AddsEcho::class);
        $cause = Event::factory()
            ->for($this->history)
            ->create();
        $event = Event::factory()
            ->for($this->history)
            ->create();

        $this
            ->withoutExceptionHandling()
            ->actingAs($this->history->owner)
            ->post(
                route('events.echoes.store', [$this->history, $event]),
                [
                    'name' => '::name::',
                    'type' => CardType::Dark->value,
                    'cause' => $cause->id,
                ],
            )
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $actionMock
            ->shouldHaveReceived('handle')
            ->once()
            ->with(
                m::on(static fn (Event $actualCause) => $actualCause->is($cause)),
                m::on(static fn (Event $actualEvent) => $actualEvent->is($event)),
                '::name::',
                CardType::Dark,
            );
        EventFacade::assertDispatched(
            BoardUpdated::class,
            fn (BoardUpdated $event): bool => $event->history->is($this->history),
        );
    }

    /**
     * @dataProvider invalidEchoPayloadProvider
     */
    public function testEchoValidation(callable $getPayload, string $expectedKey): void
    {
        $event = Event::factory()
            ->for($this->history)
            ->create();

        $this->actingAs($this->history->owner)
            ->post(
                route('events.echoes.store', [$this->history, $event]),
                $getPayload(),
            )
            ->assertRedirect()
            ->assertSessionHasErrors($expectedKey);
    }

    public function invalidEchoPayloadProvider(): Generator
    {
        yield from [
            'name missing' => [
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield from [
            'add intervention' => ['events.interventions.store'],
            'add echo' => ['events.echoes.store'],
        ];
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'add intervention' => [
                'post',
                static fn () => Event::factory()->create(),
                static fn (History $history, Event $event) => route('events.interventions.store', [$history, $event]),
            ],
            'add echo' => [
                'post',
                static fn () => Event::factory()->create(),
                static fn (History $history, Event $period) => route('events.echoes.store', [$history, $period]),
            ],
        ];
    }
}
