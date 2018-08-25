<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\Models\Appointment;
use App\Models\Process;
use App\Models\Vet;
use App\User;
use App\Helpers\EnumHelper;

/*
|--------------------------------------------------------------------------
| Appointment Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Appointment::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');
    $vets = $faker->randomElements(Vet::all()->pluck('id')->toArray(), 2);
 
    return [
        'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'vet_id_1' => $vets[0],
        'date_1' => $date,
        'vet_id_2' => $vets[1],
        'date_2' => $date,
        'amount_males' => $faker->numberBetween(0, 4),
        'amount_females' => $faker->numberBetween(0, 4),
        'notes' => $faker->text(50),
        'status' => $faker->randomElement(EnumHelper::get('appointments.status')),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
