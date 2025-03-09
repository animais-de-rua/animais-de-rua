<?php

namespace Database\Factories;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class TreatmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Treatment::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'affected_animals' => $this->faker->randomNumber(),
            'affected_animals_new' => $this->faker->randomNumber(),
            'status'=> $this->faker->randomElement(['approving','approved']),
            'date'=> $this->faker->date(),
        ];
    }
}
