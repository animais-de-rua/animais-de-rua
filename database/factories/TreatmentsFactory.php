<?php

use App\Models\Process;
use App\Models\Treatment;
use App\Models\TreatmentType;
use App\Models\Vet;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Treatment Model Factory
|--------------------------------------------------------------------------
*/

$factory->define(Treatment::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
        'treatment_type_id' => $faker->randomElement(TreatmentType::all()->pluck('id')->toArray()),
        'vet_id' => $faker->randomElement(Vet::all()->pluck('id')->toArray()),
        'expense' => $faker->randomElement([0, 0, 0, 0, 10, 10, 20, 50, 80, 120, 150]),
        'affected_animals' => $faker->randomElement([1, 1, 1, 2, 3, 4, 6]),
        'date' => $date,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
