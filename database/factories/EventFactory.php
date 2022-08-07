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
use App\EventType;
use App\History;
use App\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'type' => $this->faker->randomElement([CardType::Light, CardType::Dark]),
            'position' => 1,
            'period_id' => static fn () => Period::factory(),
            'history_id' => static fn () => History::factory(),
            'event_type' => EventType::Event,
            'echo_group' => null,
            'echo_group_position' => 1,
        ];
    }

    public function intervention(): self
    {
        return $this->state([
            'event_type' => EventType::Intervention,
        ]);
    }

    public function echo(): self
    {
        return $this->state([
            'event_type' => EventType::Echo,
            'event_id' => Event::factory(),
        ]);
    }
}
