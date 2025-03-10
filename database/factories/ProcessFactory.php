<?php

namespace Database\Factories;

use App\Models\Process;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Process>
 */
class ProcessFactory extends Factory
{
    protected $model = Process::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'contact' => $this->faker->phoneNumber(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'territory_id' => Territory::factory(),
        ];
    }
}
