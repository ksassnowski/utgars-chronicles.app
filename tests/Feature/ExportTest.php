<?php declare(strict_types=1);

namespace Tests\Feature;

use Mockery;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use Tests\GameRouteTest;
use App\Export\HistoryExporter;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, GameRouteTest;

    /** @test */
    public function canDownloadExportOfHistory(): void
    {
        $history = History::factory()->create();
        $history->periods()->create(Period::factory()->make()->toArray());
        $exporterMock = Mockery::mock(HistoryExporter::class);
        $exporterMock->shouldReceive('export')
            ->once();

        app()->instance(HistoryExporter::class, $exporterMock);

        $response = $this->actingAs($history->owner)->getJson(route('history.export', $history));

        $response->assertOk();
    }

    public function authenticatedRoutesProvider(): Generator
    {
        yield from [
            'download export' => [
                'get',
                fn (History $history) => route('history.export', $history),
                fn () => History::factory()->create(),
            ],
        ];
    }

    public function gameRouteProvider(): Generator
    {
        yield ['history.export'];
    }
}
