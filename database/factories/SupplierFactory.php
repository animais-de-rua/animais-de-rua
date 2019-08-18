<?php

use App\Helpers\EnumHelper;
use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\Models\Supplier;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Store Product and Store Order Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(Supplier::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');

    return [
        'reference' => $faker->isbn10(),
        'store_order_id' => $faker->randomElement(StoreOrder::all()->pluck('id')->toArray()),
        'store_product_id' => $faker->randomElement(StoreProduct::all()->pluck('id')->toArray()),
        'notes' => $faker->text(80),
        'status' => $faker->randomElement(EnumHelper::get('store.supplier')),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
