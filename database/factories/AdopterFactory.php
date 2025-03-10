<?php

namespace Database\Factories;

use App\Models\Adopter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Adopter>
 */
class AdopterFactory extends Factory
{
    protected $model = Adopter::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'zip_code' => $this->faker->postcode(),
            'id_card' => $this->faker->creditCardNumber(),
        ];
    }
}
