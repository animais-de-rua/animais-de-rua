<?php

namespace Database\Factories;

use App\Models\StoreProduct;
use App\Models\StoreStock;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreStockFactory extends Factory
{
    protected $model = StoreStock::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'store_product_id' => $faker->randomElement(StoreProduct::all()->pluck('id')->toArray()),
            'quantity' => $faker->randomElement([5, 10, 20, 50]) * (rand(0, 1) ? -1 : 1),
            'notes' => $faker->text(80),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
