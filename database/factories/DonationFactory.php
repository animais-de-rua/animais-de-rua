<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\Models\Donation;
use App\Models\Process;
use App\Models\Godfather;
use App\Helpers\EnumHelper;

/*
|--------------------------------------------------------------------------
| Donation Model Factory
|--------------------------------------------------------------------------
*/

$factory->define(Donation::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
        'godfather_id' => $faker->randomElement(Godfather::all()->pluck('id')->toArray()),
        'value' => $faker->randomElement([5, 10, 20, 50]),
        'status' => $faker->randomElement(EnumHelper::get('donation.status')),
        'date' => $date,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
