<?php

namespace Database\Factories;

use App\Helpers\EnumHelper;
use App\Models\StoreOrder;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreOrderFactory extends Factory
{
    protected $model = StoreOrder::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');
        $status = $faker->randomElement(EnumHelper::get('store.order'));
        $sent = $status == 'shipped';

        return [
            'reference' => $faker->isbn10(),
            'recipient' => $faker->firstName . ' ' . $faker->lastName,
            'address' => $faker->address,
            'user_id' => $faker->randomElement(User::all()->pluck('id')->toArray()),
            'notes' => $faker->text(80),
            'status' => $status,
            'shipment_date' => $sent ? $date : null,
            'expense' => $sent ? $faker->randomFloat(2, 1, 3) : 0,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
