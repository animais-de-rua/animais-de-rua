<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voucher>
 */
class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        return [
            'reference' => $this->faker->word,
            'value' => $this->faker->randomNumber(5),
            'status' => $this->faker->randomElement(['used', 'unused']),
        ];
    }
}
