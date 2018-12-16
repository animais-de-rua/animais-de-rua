<?php

use App\Helpers\EnumHelper;
use App\Models\Appointment;
use App\Models\Process;
use App\Models\Vet;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Appointment Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Appointment::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', '+1 month');
    $vets = $faker->randomElements(Vet::all()->pluck('id')->toArray(), 2);
    $amountDefined = $faker->boolean;

    return [
        'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'vet_id_1' => $vets[0],
        'date_1' => $date,
        'vet_id_2' => $vets[1],
        'date_2' => $date,
        'amount_males' => $amountDefined ? $faker->numberBetween(0, 4) : 0,
        'amount_females' => $amountDefined ? $faker->numberBetween(0, 4) : 0,
        'amount_other' => $amountDefined ? 0 : $faker->numberBetween(0, 8),
        'notes' => $faker->text(50),
        'status' => $faker->randomElement(EnumHelper::get('appointment.status')),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
