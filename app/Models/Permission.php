<?php

namespace App\Models;

use App\Enums\PermissionsEnum;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * Find a permission based on the enum
     */
    public static function find(PermissionsEnum $enum): ?Permission
    {
        return static::where(['name' => $enum->value])->first();
    }
}
