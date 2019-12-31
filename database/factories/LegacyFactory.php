<?php declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Legacy;
use App\History;
use Faker\Generator as Faker;

$factory->define(Legacy::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'history_id' => fn () => factory(History::class)->create()->id,
    ];
});
