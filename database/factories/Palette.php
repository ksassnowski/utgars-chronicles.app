<?php declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\History;
use App\Palette;
use App\PaletteType;
use Faker\Generator as Faker;

$factory->define(Palette::class, fn (Faker $faker) => [
    'name' => $faker->sentence,
    'type' => $faker->randomElement([PaletteType::NO, PaletteType::YES]),
    'history_id' => fn () => factory(History::class)->create()->id,
]);
