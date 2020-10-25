<?php declare(strict_types=1);

namespace Tests\Feature;

use Mockery;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use Tests\GameRouteTest;
use App\Export\HistoryExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends TestCase
{
    use RefreshDatabase, GameRouteTest;

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

    public function gameRouteProvider(): Generator
    {
        yield ['history.export'];
    }
}
