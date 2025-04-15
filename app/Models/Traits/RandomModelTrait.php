<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @method static \Illuminate\Database\Eloquent\Factories\Factory<self> factory()
 */
trait RandomModelTrait
{
    /**
     * Gets a random entry
     */
    public static function random(): EloquentModel|null|static
    {
        return static::inRandomOrder()->first();
    }

    /**
     * Gets a random entry or a new one in case none found
     *
     * @return EloquentModel|Factory<self>|static
     */
    public static function randomOrNew(): EloquentModel|Factory|static
    {
        return static::inRandomOrder()->first() ?? self::factory();
    }
}
