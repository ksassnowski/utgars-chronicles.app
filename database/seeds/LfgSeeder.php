<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Lfg;
use Illuminate\Database\Seeder;

class LfgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lfg::factory()->count(25)->create();
        Lfg::factory()->count(50)->past()->create();
    }
}
