<?php

namespace Database\Factories;

use App\Models\StoreProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreProduct>
 */
class StoreProductFactory extends Factory
{
    protected $model = StoreProduct::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomNumber(5),
            'price_no_vat' => $this->faker->randomNumber(5),
        ];
    }
}
