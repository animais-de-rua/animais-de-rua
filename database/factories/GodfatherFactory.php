<?php

namespace Database\Factories;

use App\Models\Godfather;
use App\Models\Territory;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GodfatherFactory extends Factory
{
    protected $model = Godfather::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'name' => $faker->firstName . ' ' . $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
