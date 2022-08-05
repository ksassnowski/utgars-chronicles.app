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

use App\History;
use App\MicroscopeGameMode;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'public' => false,
            'game_mode' => MicroscopeGameMode::BaseGame,
            'owner_id' => User::factory(),
        ];
    }

    public function public(): self
    {
        return $this->state([
            'public' => true,
        ]);
    }

    public function echo(): self
    {
        return $this->state([
            'game_mode' => MicroscopeGameMode::Echo,
        ]);
    }
}
