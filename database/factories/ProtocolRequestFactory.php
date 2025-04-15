<?php

namespace Database\Factories;

use App\Models\ProtocolRequest;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProtocolRequest>
 */
class ProtocolRequestFactory extends Factory
{
    protected $model = ProtocolRequest::class;

    public function definition(): array
    {
        return [
            'council' => $this->faker->city(),
            'name' => $this->faker->name,
            'territory_id' => Territory::randomOrNew(),
        ];
    }
}
