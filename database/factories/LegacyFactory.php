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
use App\Legacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegacyFactory extends Factory
{
    protected $model = Legacy::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'history_id' => History::factory(),
        ];
    }
}
