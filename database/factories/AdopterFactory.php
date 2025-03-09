<?php

namespace Database\Factories;

use App\Models\Adopter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class AdopterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Adopter::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'zip_code' => $this->faker->postcode(),
            'id_card' => $this->faker->creditCardNumber(),
        ];
    }
}
