<?php declare(strict_types=1);

namespace Database\Factories;

use App\User;
use App\History;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'public' => false,
            'owner_id' => User::factory(),
        ];
    }

    public function public(): HistoryFactory
    {
        return $this->state([
            'public' => true,
        ]);
    }
}
