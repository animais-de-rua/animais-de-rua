<?php

if (!function_exists('api')) {
    function api()
    {
        return app('App\Http\Controllers\Admin\APICrudController');
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
    function hasPermission($permission)
    {
        return backpack_user() && backpack_user()->hasPermission($permission);
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
            return hasRole($roles) || hasPermission($permissions);
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

if (!function_exists('is')) {
    function is($roles, $permissions = null)
    {
        return restrictTo($roles, $permissions);
    }
}
