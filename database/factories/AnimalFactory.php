<?php

use App\Helpers\EnumHelper;
use App\Models\Adoption;
use App\Models\Animal;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Donation Model Factory
|--------------------------------------------------------------------------
*/

$factory->define(Animal::class, function (Faker $faker) {
    $faker->addProvider(new \App\Providers\FakerServiceProvider($faker));
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'adoption_id' => $faker->randomElement(Adoption::all()->pluck('id')->toArray()),
        'name' => $faker->animal,
        'age' => [rand(0, 5), rand(0, 12)],
        'gender' => $faker->randomElement(EnumHelper::get('animal.gender')),
        'sterilized' => rand(0, 1),
        'vaccinated' => rand(0, 1),
        'created_at' => $date,
        'updated_at' => $date
    ];
});
