<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\User;

/*
|--------------------------------------------------------------------------
| Use Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(User::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'name' => $faker->firstName.' '.$faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$ZoyR5TtV3.V.QCMvYVRL2.LFaO13PGl50Bfxxz629zY.fY8BzoIfe',
        'remember_token' => str_random(10),
        'created_at' => $date,
        'updated_at' => $date
    ];
});
