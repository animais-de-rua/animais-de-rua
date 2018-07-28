<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\Models\Godfather;
use App\Models\Territory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Godfather::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'name' => $faker->firstName.' '.$faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
