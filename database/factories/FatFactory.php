<?php

namespace Database\Factories;

use App\Models\Fat;
use App\Models\Territory;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FatFactory extends Factory
{
    protected $model = Fat::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'name' => $faker->firstName . ' ' . $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'territory_id' => $faker->randomElement(Territory::all()->pluck('id')->toArray()),
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'notes' => $faker->text(80),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
