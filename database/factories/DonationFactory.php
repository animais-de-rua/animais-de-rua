<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Process;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'process_id' => Process::factory(),
            'type' => $this->faker->randomElement(['private', 'headquarter', 'protocol']),
        ];
    }
}
