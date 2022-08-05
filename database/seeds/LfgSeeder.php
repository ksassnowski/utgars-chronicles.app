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

use App\Lfg;
use Illuminate\Database\Seeder;

class LfgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lfg::factory()->count(25)->create();
        Lfg::factory()->count(50)->past()->create();
    }
}
