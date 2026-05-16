<?php

namespace Database\Factories;

use App\Helpers\EnumHelper;
use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        $faker = $this->faker;
        $date = $faker->dateTimeBetween('-2 months', 'now');

        return [
            'reference' => $faker->isbn10(),
            'store_order_id' => $faker->randomElement(StoreOrder::all()->pluck('id')->toArray()),
            'store_product_id' => $faker->randomElement(StoreProduct::all()->pluck('id')->toArray()),
            'notes' => $faker->text(80),
            'status' => $faker->randomElement(EnumHelper::get('store.supplier')),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
