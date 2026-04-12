<?php

namespace Database\Factories;

use App\Helpers\EnumHelper;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');
        $expiration = $faker->dateTimeBetween('2 months', '1 year');
        $valueOrPercent = mt_rand(0, 1);

        return [
            'reference' => $faker->isbn10(),
            'voucher' => $faker->isbn10(),
            'value' => $valueOrPercent ? $faker->randomElement([5, 10, 20, 50]) : null,
            'percent' => !$valueOrPercent ? $faker->randomElement([5, 10, 20]) : null,
            'client_name' => $faker->firstName . ' ' . $faker->lastName,
            'client_email' => $faker->unique()->safeEmail,
            'expiration' => $expiration,
            'status' => $faker->randomElement(EnumHelper::get('store.voucher')),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
