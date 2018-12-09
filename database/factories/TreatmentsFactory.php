<?php

use App\Helpers\EnumHelper;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\TreatmentType;
use App\Models\Vet;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Treatment Model Factory
|--------------------------------------------------------------------------
*/

$factory->define(Treatment::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'appointment_id' => $faker->randomElement(Appointment::all()->pluck('id')->toArray()),
        'treatment_type_id' => $faker->randomElement(TreatmentType::all()->pluck('id')->toArray()),
        'vet_id' => $faker->randomElement(Vet::all()->pluck('id')->toArray()),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'expense' => $faker->randomElement([0, 0, 0, 0, 10, 10, 20, 50, 80, 120, 150]),
        'affected_animals' => $faker->randomElement([1, 1, 1, 2, 3, 4, 6]),
        'affected_animals_new' => $faker->randomElement([0, 0, 0, 1, 1, 1, 2]),
        'date' => $date,
        'status' => $faker->randomElement(EnumHelper::get('treatment.status')),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
