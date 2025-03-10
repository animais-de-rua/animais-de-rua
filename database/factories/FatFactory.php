<?php

namespace Database\Factories;

use App\Models\Fat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fat>
 */
class FatFactory extends Factory
{
    protected $model = Fat::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
