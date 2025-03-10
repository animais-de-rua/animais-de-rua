<?php

namespace Database\Factories;

use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vet>
 */
class VetFactory extends Factory
{
    protected $model = Vet::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
