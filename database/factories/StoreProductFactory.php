<?php

namespace Database\Factories;

use App\Models\StoreProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreProductFactory extends Factory
{
    protected $model = StoreProduct::class;

    public function definition()
    {
        $faker = $this->faker;
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
    }
}
