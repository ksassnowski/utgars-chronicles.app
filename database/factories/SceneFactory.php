<?php declare(strict_types=1);

namespace Database\Factories;

use App\CardType;
use App\Event;
use App\Scene;
use App\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class SceneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Scene::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->sentence . '?',
            'scene' => $this->faker->paragraph,
            'answer' => $this->faker->paragraph,
            'type' => $this->faker->randomElement([CardType::Light, CardType::Dark]),
            'position' => 1,
            'event_id' => Event::factory(),
            'history_id' => History::factory(),
        ];
    }
}
