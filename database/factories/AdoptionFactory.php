<?php

use App\Helpers\EnumHelper;
use App\Models\Adoption;
use App\Models\Process;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Donation Model Factory
|--------------------------------------------------------------------------
*/

$factory->define(Adoption::class, function (Faker $faker) {
    $faker->addProvider(new \App\Providers\FakerServiceProvider($faker));

    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'fat_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'name' => $faker->animal,
        'age' => [rand(0, 5), rand(0, 12)],
        'gender' => $faker->randomElement(EnumHelper::get('animal.gender')),
        'sterilized' => rand(0, 1),
        'vaccinated' => rand(0, 1),
        'history' => $faker->text(80),
        'status' => $faker->randomElement(EnumHelper::get('adoption.status')),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
