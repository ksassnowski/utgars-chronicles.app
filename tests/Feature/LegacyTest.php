<?php declare(strict_types=1);

namespace Tests\Feature;

use Generator;
use App\Legacy;
use App\History;
use Tests\TestCase;
use Tests\GameRouteTest;
use Tests\ScopedRouteTest;
use App\Events\LegacyCreated;
use App\Events\LegacyDeleted;
use App\Events\LegacyUpdated;
use Tests\ValidateRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Http\Requests\Legacy\CreateLegacyRequest;
use App\Http\Requests\Legacy\UpdateLegacyRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Legacy\CreateLegacyController;
use App\Http\Controllers\Legacy\UpdateLegacyController;

class LegacyTest extends TestCase
{
    use RefreshDatabase, ValidateRoutesTest, ScopedRouteTest, GameRouteTest;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function createLegacy(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)
            ->postJson(route('history.legacies.store', $history), [
                'name' => '::legacy-name::',
            ]);

        $response->assertStatus(201);
        $history->refresh();
        $this->assertTrue($history->legacies->contains('name', '::legacy-name::'));
        Event::assertDispatched(
            LegacyCreated::class,
            fn (LegacyCreated $event) => $event->legacy->name === '::legacy-name::' && $event->legacy->history_id === $history->id
        );
    }

    /** @test */
    public function updateLegacy(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $legacy = $history->addLegacy('::old-name::');

        $response = $this->actingAs($history->owner)
            ->putJson(route('legacies.update', [$history, $legacy]), [
                'name' => '::new-name::'
            ]);

        $response->assertOk();
        $legacy->refresh();
        $this->assertEquals('::new-name::', $legacy->name);
        Event::assertDispatched(
            LegacyUpdated::class,
            fn (LegacyUpdated $event) => $event->legacy->id === $legacy->id
        );
    }

    /** @test */
    public function deleteLegacy(): void
    {
        /** @var History $history */
        $history = History::factory()->create();
        $legacy = $history->addLegacy('::legacy-name::');

        $response = $this->actingAs($history->owner)
            ->deleteJson(route('legacies.delete', [$history, $legacy]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('legacies', [
            'id' => $legacy->id,
        ]);
        Event::assertDispatched(
            LegacyDeleted::class,
            fn (LegacyDeleted $event) => $event->legacyId === $legacy->id && $event->history->id === $history->id
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
            ]
        ];
    }

    public function scopedRouteProvider(): Generator
    {
        yield from [
            'update legacy' => [
                'put',
                fn () => Legacy::factory()->create(),
                fn (History $history, Legacy $legacy) => route('legacies.update', [$history, $legacy]),
            ],
            'delete legacy' => [
                'delete',
                fn () => Legacy::factory()->create(),
                fn (History $history, Legacy $legacy) => route('legacies.delete', [$history, $legacy]),
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
