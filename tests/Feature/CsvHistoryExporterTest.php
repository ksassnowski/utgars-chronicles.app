<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Type;
use App\Event;
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
        $history = History::factory()->create();
        Period::factory()->create([
            'history_id' => $history->id,
            'name' => '::period-1::',
            'type' => Type::DARK,
            'position' => 2
        ]);
        Period::factory()->create([
            'history_id' => $history->id,
            'name' => '::period-2::',
            'type' => Type::LIGHT,
            'position' => 1,
        ]);
        Period::factory()->create([
            'history_id' => $history->id,
            'name' => '::period-3::',
            'type' => Type::DARK,
            'position' => 3,
        ]);

        $result = (new CsvHistoryExporter())->export($history);

        self::assertEquals(
            [['PERIOD (light): ::period-2::', 'PERIOD (dark): ::period-1::', 'PERIOD (dark): ::period-3::']],
            $result
        );
    }

    /** @test */
    public function exportEventsInCorrectOrder(): void
    {
        $history = History::factory()->create();
        Period::factory()->create([
            'history_id' => $history->id,
            'name' => '::period-2::',
            'type' => Type::DARK,
            'position' => 2,
        ]);
        $period = Period::factory()->create([
            'history_id' => $history->id,
            'name' => '::period-1::',
            'type' => Type::DARK,
            'position' => 1,
        ]);
        Event::factory()->create([
            'history_id' => $history->id,
            'period_id' => $period->id,
            'name' => '::event-1::',
            'type' => Type::LIGHT,
            'position' => 2,
        ]);
        Event::factory()->create([
            'history_id' => $history->id,
            'period_id' => $period->id,
            'name' => '::event-2::',
            'type' => Type::DARK,
            'position' => 1,
        ]);

        $result = (new CsvHistoryExporter())->export($history);

        self::assertEquals(
            [
                ['PERIOD (dark): ::period-1::', 'PERIOD (dark): ::period-2::'],
                ['EVENT (dark): ::event-2::', null],
                ['EVENT (light): ::event-1::', null],
            ],
            $result
        );
    }

    /** @test */
    public function handleHistoryWithOnlyASinglePeriod(): void
    {
        $history = History::factory()->create();
        $period = Period::factory()->create([
            'history_id' => $history->id,
            'name' => '::period-1::',
            'type' => Type::DARK,
            'position' => 1,
        ]);
        Event::factory()->create([
            'history_id' => $history->id,
            'period_id' => $period->id,
            'name' => '::event-1::',
            'type' => Type::DARK,
            'position' => 1,
        ]);

        $result = (new CsvHistoryExporter())->export($history);

        self::assertEquals(
            [
                ['PERIOD (dark): ::period-1::'],
                ['EVENT (dark): ::event-1::'],
            ],
            $result
        );
    }
}
