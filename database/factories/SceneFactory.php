<?php declare(strict_types=1);

namespace Database\Factories;

use App\Type;
use App\Event;
use App\Scene;
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
            'type' => $this->faker->randomElement([Type::LIGHT, Type::DARK]),
            'position' => 1,
            'event_id' => Event::factory(),
        ];
    }
}
