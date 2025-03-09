<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Process;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class DonationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Donation::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'process_id' => Process::factory(),
            'type' => $this->faker->randomElement(['private', 'headquarter', 'protocol']),
        ];
    }
}
