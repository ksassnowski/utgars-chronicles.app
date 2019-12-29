<?php declare(strict_types=1);

use App\History;
use Illuminate\Database\Seeder;

class HistoriesSeeder extends Seeder
{
    public function run(): void
    {
        factory(History::class)->create(['owner_id' => 1]);
    }
}
