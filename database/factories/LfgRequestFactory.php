<?php declare(strict_types=1);

namespace Database\Factories;

use App\Lfg;
use App\User;
use App\LfgRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class LfgRequestFactory extends Factory
{
    protected $model = LfgRequest::class;

    public function definition(): array
    {
        return [
            'lfg_id' => Lfg::factory(),
            'user_id' => User::factory(),
            'message' => $this->faker->optional()->paragraph,
        ];
    }

    public function pending(): self
    {
        return $this->state([
            'accepted_at' => null,
            'rejected_at' => null,
        ]);
    }

    public function accepted(): self
    {
        return $this->state([
            'accepted_at' => $this->faker->dateTime,
            'rejected_at' => null,
        ]);
    }

    public function rejected(): self
    {
        return $this->state([
            'rejected_at' => $this->faker->dateTimeThisMonth,
            'accepted_at' => null,
        ]);
    }
}
