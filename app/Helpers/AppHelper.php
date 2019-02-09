<?php

if (!function_exists('api')) {
    function api()
    {
        return app('App\Http\Controllers\Admin\APICrudController');
    }
}

if (!function_exists('collect_only')) {
    function collect_only($model, $attributes)
    {
        return collect($model->toArray())->only($attributes)->all();
    }
}

if (!function_exists('data_get_first')) {
    function data_get_first($target, $key, $attribute, $default = 0)
    {
        $value = data_get($target, $key);
        return count($value) ? $value[0]->{$attribute} : $default;
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role)
    {
        return backpack_user() && backpack_user()->hasRole($role);
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($permissions)
    {
        if (backpack_user()) {
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }

            foreach ($permissions as $permission) {
                if (backpack_user()->checkPermissionTo($permission, backpack_guard_name())) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return backpack_user() && backpack_user()->hasRole('admin');
    }
}

if (!function_exists('volunteer')) {
    function volunteer()
    {
        return backpack_user() && backpack_user()->hasRole('volunteer');
    }
}

if (!function_exists('restrictTo')) {
    function restrictTo($roles, $permissions = null)
    {
        $session_role = Session::get('role', null);
        $session_permissions = Session::get('permissions', null);

        // Default
        if (!$session_role && !$session_permissions) {
            return ($roles && hasRole($roles)) || ($permissions && hasPermission($permissions));
        }

        // View as
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        // View as Role only
        if ($session_role && (!$session_permissions || !$permissions)) {
            return in_array($session_role, $roles);
        }

        // View as Role and Permissions
        else if ($session_role && $session_permissions && $permissions) {
            return in_array($session_role, $roles) || sizeof(array_intersect($session_permissions, array_values($permissions)));
        }
    }
}

if (!function_exists('restrictToHeadquarters')) {
    function restrictToHeadquarters()
    {
        return is('admin') ? null : (Session::get('headquarters', null) ?: backpack_user()->headquarters->pluck('id')->toArray() ?: null);
    }
}

if (!function_exists('is')) {
    function is($roles, $permissions = null)
    {
        return restrictTo($roles, $permissions);
    }
}
