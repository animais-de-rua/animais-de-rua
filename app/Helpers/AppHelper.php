<?php

if (! function_exists('api')) {
    function api() {
        return app('App\Http\Controllers\Admin\APICrudController');
    }
}

if (! function_exists('data_get_first')) {
    function data_get_first($target, $key, $attribute, $default = 0) {
        $value = data_get($target, $key);
        return sizeof($value) ? $value[0]->{$attribute} : $default;
    }
}