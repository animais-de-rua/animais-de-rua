<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

use App\Models\Vet;
use App\Models\Headquarter;
use App\Helpers\EnumHelper;

/*
|--------------------------------------------------------------------------
| Vet Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Vet::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'name' => 'Clínica '.$faker->company,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'url' => $faker->domainName,
        'latlong' => $faker->latitude(41.76, 37.26).', '.$faker->longitude(-8.62, -7.21),
        'headquarter_id' => $faker->randomElement(Headquarter::all()->pluck('id')->toArray()),
        'status' => $faker->randomElement(EnumHelper::get('vet.status')),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
