<?php

namespace Helpers;

class ArrayHelper
{
    public static function getValue(string $key, array $array)
    {
        if (!array_key_exists($key, $array)) {
            return false;
        }

        return $array[$key];
    }
}