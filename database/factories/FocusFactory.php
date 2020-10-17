<?php declare(strict_types=1);

namespace Database\Factories;

use App\Focus;
use App\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class FocusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Focus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'history_id' => History::factory(),
        ];
    }
}
