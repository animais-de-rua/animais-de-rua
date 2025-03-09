<?php

namespace Database\Factories;

use App\Models\Godfather;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory<User>
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
