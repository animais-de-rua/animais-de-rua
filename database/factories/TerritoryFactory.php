<?php

namespace Database\Factories;

use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Territory>
 */
class TerritoryFactory extends Factory
{
    protected $model = Territory::class;

    public function definition(): array
    {
        return [
            'id' => Str::random(6),
            'name' => $this->faker->name(),
            'level' => $this->faker->randomElement(['1', '2', '3']),
        ];
    }
}
