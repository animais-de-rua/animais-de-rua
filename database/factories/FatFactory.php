<?php

use App\Models\Fat;
use App\Models\Territory;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| FAT Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Fat::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'notes' => $faker->text(80),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
