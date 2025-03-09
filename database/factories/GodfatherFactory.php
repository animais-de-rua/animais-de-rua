<?php

namespace Database\Factories;

use App\Models\Godfather;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class GodfatherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Godfather::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'alias' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->sentence(),
            'territory_id' => Territory::factory(),
            'user_id' => User::factory(),
        ];
    }
}
