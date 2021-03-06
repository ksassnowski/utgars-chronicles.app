<?php declare(strict_types=1);

namespace Database\Factories;

use App\Legacy;
use App\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegacyFactory extends Factory
{
    protected $model = Legacy::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'history_id' => History::factory(),
        ];
    }
}
