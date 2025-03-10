<?php

namespace Database\Factories;

use App\Models\Adoption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Adoption>
 */
class AdoptionFactory extends Factory
{
    protected $model = Adoption::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'age' => [$this->faker->numberBetween(1, 12), $this->faker->randomDigitNotZero()],
            'sterilized' => $this->faker->randomElement([1, 0]),
            'vaccinated' => $this->faker->randomElement([1, 0]),
            'processed' => $this->faker->randomElement([1, 0]),
            'individual' => $this->faker->randomElement([1, 0]),
            'docile' => $this->faker->randomElement([1, 0]),
            'abandoned' => $this->faker->randomElement([1, 0]),
            'foal' => $this->faker->randomElement([1, 0]),
            'adoption_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['open', 'pending', 'closed', 'archived']),

        ];
    }
}
