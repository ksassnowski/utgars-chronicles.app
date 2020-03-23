<?php declare(strict_types=1);

namespace App\Export;

use App\Period;
use App\History;

final class CsvHistoryExporter implements HistoryExporter
{
    public function export(History $history): array
    {
        return $history->periods->map(function (Period $period) {
            $row = [sprintf('%s (%s)', $period->name, $period->type)];

            foreach ($period->events as $event) {
                $row[] = sprintf('%s (%s)', $event->name, $event->type);

                foreach ($event->scenes as $scene) {
                    $row[] = sprintf("%s\n\n%s\n\n%s\n\n(%s)", $scene->question, $scene->scene, $scene->answer, $scene->type);
                }
            }

            return $row;
        })->transpose();
    }
}
