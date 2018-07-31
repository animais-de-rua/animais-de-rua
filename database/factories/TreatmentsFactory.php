<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\Models\Process;
use App\Models\Treatment;
use App\Models\TreatmentType;
use App\Models\Vet;
use App\Helpers\EnumHelper;

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
        'expense' => $faker->randomElement([0, 0, 0, 0, 10, 10, 20, 50, 80]),
        'date' => $date,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
