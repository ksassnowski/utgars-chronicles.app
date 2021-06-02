<?php declare(strict_types=1);

namespace Database\Factories;

use App\Lfg;
use Illuminate\Database\Eloquent\Factories\Factory;

class LfgFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lfg::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slots' => $this->faker->numberBetween(2, 6),
            'start_date' => $this->faker->dateTimeBetween('now', '+2weeks', 'UTC')
        ];
    }

    public function past(): self
    {
        return $this->state([
            'start_date' => $this->faker->dateTime('-1hour', 'UTC'),
        ]);
    }
}
