<?php declare(strict_types=1);

namespace App\Export;

use App\History;

interface HistoryExporter
{
    public function export(History $history): array;
}
