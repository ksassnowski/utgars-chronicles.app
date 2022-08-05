<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace Database\Factories;

use App\CardType;
use App\Event;
use App\History;
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
            'type' => $this->faker->randomElement([CardType::Light, CardType::Dark]),
            'position' => 1,
            'event_id' => Event::factory(),
            'history_id' => History::factory(),
        ];
    }
}
