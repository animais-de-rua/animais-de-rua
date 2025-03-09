<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'amount_males' => $this->faker->randomDigitNotZero(),
            'amount_females' => $this->faker->randomDigitNotZero(),
            'amount_other' => $this->faker->randomDigitNotZero(),
            'status' => $this->faker->randomElement(['approving', 'approved_option_1', 'approved_option_2']),
        ];
    }
}
