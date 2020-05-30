<?php declare(strict_types=1);

namespace Tests\Feature;

use Mockery;
use Generator;
use App\Period;
use App\History;
use Tests\TestCase;
use App\Export\HistoryExporter;
use Tests\AuthorizeHistoryTest;
use Tests\AuthenticatedRoutesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportTest extends TestCase
{
    use RefreshDatabase, AuthenticatedRoutesTest, AuthorizeHistoryTest;

    /** @test */
    public function canDownloadExportOfHistory(): void
    {
        $history = factory(History::class)->create();
        $history->periods()->create(factory(Period::class)->make()->toArray());
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
            'download export' => ['GET', '/histories/1/export'],
        ];
    }

    public function authorizationProvider(): Generator
    {
        yield from [
            'download export' => [
                [],
                fn (History $history) => route('history.export', $history),
                'get',
                200,
                function (History $history) {
                    $history->periods()->create(factory(Period::class)->make()->toArray());
                    return $history;
                },
            ],
        ];
    }
}
