<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'amount_males' => $this->faker->randomDigitNotZero(),
            'amount_females' => $this->faker->randomDigitNotZero(),
            'amount_other' => $this->faker->randomDigitNotZero(),
            'status' => $this->faker->randomElement(['approving', 'approved_option_1', 'approved_option_2']),
        ];
    }
}
