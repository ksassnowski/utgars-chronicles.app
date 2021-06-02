<?php declare(strict_types=1);

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
