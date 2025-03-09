<?php

namespace Database\Factories;

use App\Models\Process;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class ProcessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Process::class;

    /**
     * Define the model's default state.
     */
    public function definition()
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
