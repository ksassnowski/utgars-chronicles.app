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

use App\Export\HistoryExporter;
use App\History;
use App\Period;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\GameRouteTest;
use Tests\TestCase;

/**
 * @internal
 */
final class ExportTest extends TestCase
{
    use RefreshDatabase;
    use GameRouteTest;

    public function testCanDownloadExportOfHistory(): void
    {
        $history = History::factory()->create();
        $history->periods()->create(Period::factory()->make()->toArray());
        $exporterMock = \Mockery::mock(HistoryExporter::class);
        $exporterMock->shouldReceive('export')
            ->once();

        app()->instance(HistoryExporter::class, $exporterMock);

        $response = $this->actingAs($history->owner)->getJson(route('history.export', $history));

        $response->assertOk();
    }

    public static function gameRouteProvider(): \Generator
    {
        yield ['history.export'];
    }
}
