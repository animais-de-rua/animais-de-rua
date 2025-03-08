<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class EnumHelper
{
    public static function get($name)
    {
        return Config::get("enums.$name");
    }

    public static function values($name, $join = null)
    {
        $data = array_values(self::get($name));
        return $join ? join($join, $data) : $data;
    }

    public static function keys($name, $join = null)
    {
        $data = array_keys(self::get($name));
        return $join ? join($join, $data) : $data;
    }

    public static function translate($name)
    {
        $enum = [];
        foreach (self::get($name) as $key => $value) {
            $enum[$key] = ucfirst(__($value));
        }

        return $enum;
    }
}
