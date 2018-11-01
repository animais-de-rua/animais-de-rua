<?php

use App\Helpers\EnumHelper;
use App\Models\Headquarter;
use App\Models\Process;
use App\Models\Territory;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Process Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Process::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');
    $specie = $faker->randomElement(EnumHelper::get('process.specie'));
    $amountDefined = $faker->boolean;

    return [
        'name' => 'Colónia de ' . $faker->streetName,
        'contact' => $faker->firstName . ' ' . $faker->lastName,
        'phone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->address,
        'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
        'headquarter_id' => $faker->randomElement(Headquarter::all()->pluck('id')->toArray()),
        'specie' => $specie,
        'amount_males' => $amountDefined ? $faker->numberBetween(0, 4) : 0,
        'amount_females' => $amountDefined ? $faker->numberBetween(0, 4) : 0,
        'amount_other' => $amountDefined ? 0 : $faker->numberBetween(0, 8),
        'status' => $faker->randomElement(EnumHelper::get('process.status')),
        'history' => $faker->text(80),
        'notes' => $faker->text(80),
        'latlong' => $faker->latitude(41.76, 37.26) . ', ' . $faker->longitude(-8.62, -7.21),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
