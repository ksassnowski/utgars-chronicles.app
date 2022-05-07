<?php declare(strict_types=1);

namespace Database\Factories;

use App\CardType;
use App\Event;
use App\Period;
use App\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'type' => $this->faker->randomElement([CardType::Light, CardType::Dark]),
            'position' => 1,
            'period_id' => fn () => Period::factory(),
            'history_id' => fn () => History::factory(),
        ];
    }
}
