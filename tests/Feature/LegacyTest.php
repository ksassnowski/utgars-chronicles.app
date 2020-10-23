<?php declare(strict_types=1);

namespace Tests\Feature;

use Generator;
use App\Legacy;
use App\History;
use Tests\TestCase;
use Tests\ScopedRouteTest;
use App\Events\LegacyCreated;
use App\Events\LegacyDeleted;
use App\Events\LegacyUpdated;
use Tests\ValidateRoutesTest;
use Tests\AuthorizeHistoryTest;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Support\Facades\Event;
use App\Http\Requests\Legacy\CreateLegacyRequest;
use App\Http\Requests\Legacy\UpdateLegacyRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Legacy\CreateLegacyController;
use App\Http\Controllers\Legacy\UpdateLegacyController;

class LegacyTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, AuthorizeHistoryTest, ValidateRoutesTest, ScopedRouteTest;

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

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'create legacy' => [
                'post',
                fn (History $history) => route('history.legacies.store', $history),
                fn () => History::factory()->create(),
            ],
            'update legacy' => [
                'put',
                fn (Legacy $legacy) => route('legacies.update', [$legacy->history, $legacy]),
                fn () => Legacy::factory()->create(),
            ],
            'delete legacy' => [
                'delete',
                fn (Legacy $legacy) => route('legacies.delete', [$legacy->history, $legacy]),
                fn () => Legacy::factory()->create(),
            ],
        ];
    }

    public function authorizationProvider(): Generator
    {
        yield from [
            'create legacy' => [
                ['name' => '::legacy-name::'],
                fn (History $history) => route('history.legacies.store', $history),
                'post',
                201,
            ],
            'update legacy' => [
                ['name' => '::new-name::'],
                fn (Legacy $legacy) => route('legacies.update', [$legacy->history, $legacy]),
                'put',
                200,
                fn (History $history) => Legacy::factory()->create(['history_id' => $history->id]),
            ],
            'delete legacy' => [
                [],
                fn (Legacy $legacy) => route('legacies.delete', [$legacy->history, $legacy]),
                'delete',
                204,
                fn (History $history) => Legacy::factory()->create(['history_id' => $history->id]),
            ]
        ];
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
}
