<?php

namespace Database\Factories;

use App\Models\StoreStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreStock>
 */
class StoreStockFactory extends Factory
{
    protected $model = StoreStock::class;

    public function definition(): array
    {
        return [
            'quantity' => $this->faker->randomNumber(5),
        ];
    }
}
