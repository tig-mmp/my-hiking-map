<?php

namespace App\Utils;

class RequestUtils
{
    public static function getString(array $parameters, string $key): ?string
    {
        return array_key_exists($key, $parameters) && $parameters[$key] !== "null"
        && !is_array($parameters[$key]) && !empty(trim($parameters[$key]))
        ? trim($parameters[$key]) : null;
    }
}
