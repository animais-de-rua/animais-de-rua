<?php

namespace Database\Factories;

use App\Models\Protocol;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Protocol>
 */
class ProtocolFactory extends Factory
{
    protected $model = Protocol::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'territory_id' => Territory::randomOrNew(),
        ];
    }
}
