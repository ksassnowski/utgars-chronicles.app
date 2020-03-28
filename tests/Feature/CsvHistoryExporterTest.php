<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\Period;
use App\History;
use Tests\TestCase;
use App\Export\CsvHistoryExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsvHistoryExporterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function exportPeriodsInCorrectOrder(): void
    {
        $history = factory(History::class)->create();
        factory(Period::class)->create([
            'history_id' => $history->id,
            'name' => '::period-1::',
            'type' => Type::DARK,
        ])->update(['position' => 2]);
        factory(Period::class)->create([
            'history_id' => $history->id,
            'name' => '::period-2::',
            'type' => Type::LIGHT,
        ])->update(['position' => 1]);
        factory(Period::class)->create([
            'history_id' => $history->id,
            'name' => '::period-3::',
            'type' => Type::DARK,
        ])->update(['position' => 3]);

        $result = (new CsvHistoryExporter())->export($history);

        $this->assertEquals(
            [['PERIOD ::period-2::: (light)', 'PERIOD ::period-1::: (dark)', 'PERIOD ::period-3::: (dark)']],
            $result
        );
    }
}
