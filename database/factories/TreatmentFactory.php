<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class VetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Vet::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
