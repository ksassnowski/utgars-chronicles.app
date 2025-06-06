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

namespace App\Export;

use App\History;
use App\Period;

final class CsvHistoryExporter implements HistoryExporter
{
    /**
     * @return array<int, array<int, string>>
     */
    public function export(History $history): array
    {
        return $history->periods
            ->map(static function (Period $period) {
                $row = [\sprintf('PERIOD (%s): %s', $period->type->value, $period->name)];

                foreach ($period->events as $event) {
                    $row[] = \sprintf('EVENT (%s): %s', $event->type->value, $event->name);

                    foreach ($event->scenes as $scene) {
                        $row[] = \sprintf('SCENE (%s): %s ** %s ** %s', $scene->type?->value, $scene->question, $scene->scene, $scene->answer);
                    }
                }

                return $row;
            })
            ->transpose();
    }
}
