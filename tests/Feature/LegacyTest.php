<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Events\LegacyCreated;
use App\Events\LegacyDeleted;
use App\Events\LegacyUpdated;
use App\History;
use App\Http\Controllers\Legacy\CreateLegacyController;
use App\Http\Controllers\Legacy\UpdateLegacyController;
use App\Http\Requests\Legacy\CreateLegacyRequest;
use App\Http\Requests\Legacy\UpdateLegacyRequest;
use App\Legacy;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use Tests\TestCase;
use Tests\ValidateRoutesTest;

/**
 * @internal
 */
final class LegacyTest extends TestCase
{
    use RefreshDatabase;
    use ValidateRoutesTest;
    use ScopedRouteTest;
    use GameRouteTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function testCreateLegacy(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)
            ->postJson(route('history.legacies.store', $history), [
                'name' => '::legacy-name::',
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $history->refresh();
        self::assertTrue($history->legacies->contains('name', '::legacy-name::'));
        Event::assertDispatched(
            LegacyCreated::class,
            static fn (LegacyCreated $event) => '::legacy-name::' === $event->legacy->name && $event->legacy->history_id === $history->id,
        );
    }

    public function testUpdateLegacy(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $legacy = $history->addLegacy('::old-name::');

        $response = $this->actingAs($history->owner)
            ->putJson(route('legacies.update', [$history, $legacy]), [
                'name' => '::new-name::',
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $legacy->refresh();
        self::assertEquals('::new-name::', $legacy->name);
        Event::assertDispatched(
            LegacyUpdated::class,
            static fn (LegacyUpdated $event) => $event->legacy->id === $legacy->id,
        );
    }

    public function testDeleteLegacy(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $legacy = $history->addLegacy('::legacy-name::');

        $response = $this->actingAs($history->owner)
            ->deleteJson(route('legacies.delete', [$history, $legacy]));

        $response
            ->assertRedirect()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('legacies', [
            'id' => $legacy->id,
        ]);
        Event::assertDispatched(
            LegacyDeleted::class,
            static fn (LegacyDeleted $event) => $event->legacyId === $legacy->id && $event->history->id === $history->id,
        );
    }

    public function validationProvider(): Generator
    {
        yield from [
            'create legacy' => [
                CreateLegacyController::class,
                '__invoke',
                CreateLegacyRequest::class,
            ],
            'update legacy' => [
                UpdateLegacyController::class,
                '__invoke',
                UpdateLegacyRequest::class,
            ],
        ];
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update legacy' => [
                'put',
                static fn () => Legacy::factory()->create(),
                static fn (History $history, Legacy $legacy) => route('legacies.update', [$history, $legacy]),
            ],
            'delete legacy' => [
                'delete',
                static fn () => Legacy::factory()->create(),
                static fn (History $history, Legacy $legacy) => route('legacies.delete', [$history, $legacy]),
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.legacies.store'];

        yield ['legacies.update'];

        yield ['legacies.delete'];
    }
}
