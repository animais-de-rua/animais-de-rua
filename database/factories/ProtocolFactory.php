<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Process;
use App\Models\Protocol;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class ProtocolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Protocol::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
