<?php

namespace Database\Factories;

use App\Models\Godfather;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Godfather>
 */
class GodfatherFactory extends Factory
{
    protected $model = Godfather::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'alias' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->sentence(),
            'territory_id' => Territory::randomOrNew(),
            'user_id' => User::randomOrNew(),
        ];
    }
}
