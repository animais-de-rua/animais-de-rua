<?php

use App\Models\Headquarter;
use App\Models\Protocol;
use App\Models\Territory;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Protocol Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Protocol::class, function (Faker $faker) {
    $faker->addProvider(new \App\Providers\FakerServiceProvider($faker));

    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'name' => 'Município de ' . $faker->district,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
        'headquarter_id' => $faker->randomElement(Headquarter::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
