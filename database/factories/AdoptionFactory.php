<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\Models\Adoption;
use App\Models\Process;
use App\User;

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
        'name' => $faker->animal . (rand(0, 1) ? ' e ' . $faker->animal : ''),
        'history' => $faker->text(80),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
