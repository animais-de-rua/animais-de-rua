<?php

use Illuminate\Support\Facades\Session;

if (! function_exists('data_get_first')) {
    function data_get_first($target, $key, $attribute, $default = 0): mixed
    {
        $value = data_get($target, $key);

        return count($value) ? $value[0]->{$attribute} : $default;
    }
}

if (! function_exists('restrictToHeadquarters')) {
    function restrictToHeadquarters(): bool
    {
        return is('admin') ? null : (Session::get('headquarters', null) ?: user()->headquarters->pluck('id')->toArray() ?: null);
    }
}

if (! function_exists('thumb_image')) {
    function thumb_image(string $path): string
    {
        if (($pos = strrpos($path, '/')) !== false) {
            $path = substr_replace($path, '/thumb/', $pos, 1);
        }

        return $path;
    }
}
