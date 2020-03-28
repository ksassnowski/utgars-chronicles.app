<?php declare(strict_types=1);

namespace App\Export;

use App\Period;
use App\History;

final class CsvHistoryExporter implements HistoryExporter
{
    public function export(History $history): array
    {
        return $history->periods
            ->map(function (Period $period) {
                $row = [sprintf('PERIOD %s: (%s)', $period->name, $period->type)];

                foreach ($period->events as $event) {
                    $row[] = sprintf('EVENT %s: (%s)', $event->type, $event->name);

                    foreach ($event->scenes as $scene) {
                        $row[] = sprintf('SCENE (%s): %s ** %s ** %s', $scene->type, $scene->question, $scene->scene, $scene->answer);
                    }
                }

                return $row;
            })
            ->transpose();
    }
}
