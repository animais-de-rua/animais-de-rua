<?php

namespace App\Helpers;

use Config;
use Illuminate\Database\Eloquent\Builder;

class EnumHelper
{
    public static function get($name)
    {
        return Config::get("enums.$name");
    }

    public static function keys($name)
    {
        return join(',', array_keys(self::get($name)));
    }

    public static function translate($name)
    {
        $enum = [];
        foreach (self::get($name) as $key => $value)
            $enum[$key] = ucfirst(__($value));

        return $enum;
    }
}
?>