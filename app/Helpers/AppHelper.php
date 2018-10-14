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
        return backpack_user()->hasRole($role);
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($permission)
    {
        return backpack_user()->hasPermission($permission);
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return backpack_user()->hasRole('admin');
    }
}

if (!function_exists('volunteer')) {
    function volunteer()
    {
        return backpack_user()->hasRole('volunteer');
    }
}
