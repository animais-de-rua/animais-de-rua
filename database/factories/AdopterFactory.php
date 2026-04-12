<?php

namespace Database\Factories;

use App\Models\Adopter;
use App\Models\Territory;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdopterFactory extends Factory
{
    protected $model = Adopter::class;

    public function definition()
    {
        $faker = $this->faker;
        $faker->addProvider(new \App\Providers\FakerServiceProvider($faker));

        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'name' => $faker->firstName . ' ' . $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumber,
            'address' => $faker->streetAddress,
            'zip_code' => $faker->postcode,
            'id_card' => rand(10000000, 15000000),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
