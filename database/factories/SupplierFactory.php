<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'reference' => $this->faker->word,
            'status' => $this->faker->randomElement(['waiting_payment', 'paid_out']),
        ];
    }
}
