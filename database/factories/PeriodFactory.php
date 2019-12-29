<?php declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Period;
use App\History;
use Faker\Generator as Faker;

$factory->define(Period::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'position' => 1,
        'type' => $faker->randomElement(['light', 'dark']),
        'history_id' => function () {
            return factory(History::class)->create()->id;
        },
    ];
});
