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
use App\Palette;
use App\PaletteType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaletteFactory extends Factory
{
    protected $model = Palette::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'type' => $this->faker->randomElement([PaletteType::NO, PaletteType::YES]),
            'history_id' => History::factory(),
        ];
    }
}
