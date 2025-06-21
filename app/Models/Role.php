<?php

namespace App\Models;

use App\Enums\RolesEnum;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Find a role based on the enum
     */
    public static function find(RolesEnum $enum): ?Role
    {
        return static::where(['name' => $enum->value])->first();
    }
}
