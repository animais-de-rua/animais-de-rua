<?php

namespace Database\Factories;

use App\Helpers\EnumHelper;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

class VetFactory extends Factory
{
    protected $model = Vet::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'name' => 'Clínica ' . $faker->company,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumber,
            'url' => $faker->domainName,
            'latlong' => $faker->latitude(41.76, 37.26) . ', ' . $faker->longitude(-8.62, -7.21),
            'status' => $faker->randomElement(EnumHelper::get('vet.status')),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
