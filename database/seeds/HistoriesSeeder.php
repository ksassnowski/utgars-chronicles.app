<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Database\Seeders;

use App\History;
use Illuminate\Database\Seeder;

class HistoriesSeeder extends Seeder
{
    public function run(): void
    {
        History::factory()->create(['owner_id' => 1]);
    }
}
