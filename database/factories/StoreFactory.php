<?php

use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Vet Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(StoreProduct::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');
    $products = ['Bandeira', 'Tapete', 'Lápis', 'Vidro', 'Sapatos', 'Fivela', 'Relógio', 'Escova', 'Giz', 'Cabide', 'Prego', 'Braçadeira', 'Garrafa', 'Garfo', 'Papel', 'Botão'];
    $colors = ['azul', 'vermelho', 'verde', 'amarelo', 'castanho', 'rosa', 'laranja', 'roxo'];
    $price = $faker->numberBetween(9, 19) + .99;

    return [
        'name' => $faker->randomElement($products) . ' ' . $faker->randomElement($colors),
        'price' => $price,
        'price_no_vat' => $price / 1.23,
        'expense' => $faker->randomFloat(2, 1, 3),
        'notes' => $faker->text(80),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(StoreOrder::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween('-2 months', 'now');
    $sent = rand(0, 1);

    return [
        'reference' => $faker->isbn10(),
        'recipient' => $faker->firstName . ' ' . $faker->lastName,
        'address' => $faker->address,
        'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
        'notes' => $faker->text(80),
        'shipment_date' => $sent ? $date : null,
        'expense' => $sent ? $faker->randomFloat(2, 1, 3) : 0,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
