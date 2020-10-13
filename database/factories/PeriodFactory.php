<?php declare(strict_types=1);

namespace Database\Factories;

use App\Period;
use App\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodFactory extends Factory
{
    protected $model = Period::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'position' => 1,
            'type' => $this->faker->randomElement(['light', 'dark']),
            'history_id' => History::factory(),
        ];
    }
}
