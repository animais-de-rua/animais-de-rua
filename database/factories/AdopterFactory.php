<?php

use App\Models\Adopter;
use App\Models\Adoption;
use App\Models\Territory;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Donation Model Factory
|--------------------------------------------------------------------------
*/

$factory->define(Adopter::class, function (Faker $faker) {
    $faker->addProvider(new \App\Providers\FakerServiceProvider($faker));

    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'adoption_id' => $faker->randomElement(Adoption::all()->pluck('id')->toArray()),
        'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->streetAddress,
        'zip_code' => $faker->postcode,
        'adoption_date' => $date,
        'id_card' => rand(10000000, 15000000),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
