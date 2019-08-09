<?php

use App\Models\StoreProduct;
use App\Models\StoreStock;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Store Stock Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(StoreStock::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'description' => $faker->text(40),
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'store_product_id' => $faker->randomElement(StoreProduct::all()->pluck('id')->toArray()),
        'quantity' => $faker->randomElement([5, 10, 20, 50]) * (rand(0, 1) ? -1 : 1),
        'notes' => $faker->text(80),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
