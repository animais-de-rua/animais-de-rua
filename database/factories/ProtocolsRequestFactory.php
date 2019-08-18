<?php

use App\Models\Process;
use App\Models\Protocol;
use App\Models\ProtocolRequest;
use App\Models\Territory;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| ProtocolRequest Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(ProtocolRequest::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'council' => $faker->numberBetween(100000, 200000),
        'name' => $faker->firstName . ' ' . $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'description' => $faker->text(50),
        'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
        'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
        'protocol_id' => $faker->randomElement(Protocol::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
