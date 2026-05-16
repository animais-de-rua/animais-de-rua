<?php

namespace Database\Factories;

use App\Models\StoreTransaction;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreTransactionFactory extends Factory
{
    protected $model = StoreTransaction::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'description' => $faker->text(40),
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'amount' => $faker->randomElement([20, 50, 100, 200]) * (rand(0, 1) ? -1 : 1),
            'notes' => $faker->text(80),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
