<?php declare(strict_types=1);

/** @var Factory $factory */

use App\User;
use App\History;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(History::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
