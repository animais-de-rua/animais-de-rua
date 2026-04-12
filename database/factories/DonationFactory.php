<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Godfather;
use App\Models\Headquarter;
use App\Models\Process;
use App\Models\Protocol;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'process_id' => $faker->randomElement(Process::all()->pluck('id')->toArray()),
            'godfather_id' => $faker->randomElement(Godfather::all()->pluck('id')->toArray()),
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'headquarter_id' => $faker->randomElement(Headquarter::all()->pluck('id')->toArray()),
            'protocol_id' => $faker->randomElement(Protocol::all()->pluck('id')->toArray()),
            'value' => $faker->randomElement([5, 10, 20, 50]),
            'date' => $date,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
