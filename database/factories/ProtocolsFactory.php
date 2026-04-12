<?php

namespace Database\Factories;

use App\Models\Headquarter;
use App\Models\Protocol;
use App\Models\Territory;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProtocolsFactory extends Factory
{
    protected $model = Protocol::class;

    public function definition()
    {
        $faker = $this->faker;
        $faker->addProvider(new \App\Providers\FakerServiceProvider($faker));

        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'name' => 'Município de ' . $faker->district,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumber,
            'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
            'headquarter_id' => $faker->randomElement(Headquarter::all()->pluck('id')->toArray()),
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
