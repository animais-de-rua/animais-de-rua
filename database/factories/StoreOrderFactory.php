<?php

namespace Database\Factories;

use App\Models\StoreOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreOrder>
 */
class StoreOrderFactory extends Factory
{
    protected $model = StoreOrder::class;

    public function definition(): array
    {
        return [
            'reference' => $this->faker->word,
            'cart' => $this->faker->text,
            'recipient' => $this->faker->text,
            'address' => $this->faker->text,
            'expense' => $this->faker->randomNumber(5),
            'payment' => $this->faker->randomElement(['bank_transfer', 'paypal', 'credit_card', 'mbway']),
        ];
    }
}
