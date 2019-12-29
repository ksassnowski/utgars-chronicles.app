<?php declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Type;
use App\Event;
use App\Period;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'type' => $faker->randomElement([Type::LIGHT, Type::DARK]),
        'position' => 1,
        'period_id' => fn () => factory(Period::class)->create()->id,
    ];
});
