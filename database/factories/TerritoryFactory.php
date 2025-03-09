<?php

namespace Database\Factories;

use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class TerritoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Territory::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'id' => Str::random(6),
            'name' => $this->faker->name(),
            'level' => $this->faker->randomElement(['1', '2', '3']),
        ];
    }
}
