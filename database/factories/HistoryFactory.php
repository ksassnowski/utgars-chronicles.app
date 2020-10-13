<?php declare(strict_types=1);

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = \App\History::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'owner_id' => User::factory(),
        ];
    }
}
