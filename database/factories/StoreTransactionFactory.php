<?php

namespace Database\Factories;

use App\Models\StoreTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreTransaction>
 */
class StoreTransactionFactory extends Factory
{
    protected $model = StoreTransaction::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomNumber(5),
        ];
    }
}
