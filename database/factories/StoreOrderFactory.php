<?php

namespace Database\Factories;

use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class StoreProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = StoreProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomNumber(5),
            'price_no_vat' => $this->faker->randomNumber(5),
        ];
    }
}
