<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class EnumHelper
{
    public static function get(string $name): mixed
    {
        return Config::get("enums.{$name}");
    }

    public static function values(string $name, null|array|string $join = null): array|string
    {
        $data = array_values(self::get($name));

        return $join ? join($join, $data) : $data;
    }

    public static function keys(string $name, null|array|string $join = null): array|string
    {
        $data = array_keys(self::get($name));

        return $join ? join($join, $data) : $data;
    }

    public static function translate(string $name): array
    {
        $enum = [];
        foreach (self::get($name) as $key => $value) {
            $enum[$key] = ucfirst(__($value));
        }

        return $enum;
    }
}
