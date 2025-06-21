<?php

namespace Database\Factories;

use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Treatment>
 */
class TreatmentFactory extends Factory
{
    protected $model = Treatment::class;

    public function definition(): array
    {
        return [
            'affected_animals' => $this->faker->randomNumber(),
            'affected_animals_new' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(['approving', 'approved']),
            'date' => $this->faker->date(),
        ];
    }
}
