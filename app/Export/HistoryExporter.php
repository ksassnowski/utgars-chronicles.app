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

interface HistoryExporter
{
    /**
     * @return array<int, array<int, string>>
     */
    public function export(History $history): array;
}
