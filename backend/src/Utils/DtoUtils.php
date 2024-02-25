<?php

namespace App\Utils;

use DateTime;

class DtoUtils
{
    public static function getString(array $parameters, string $key): ?string
    {
        return array_key_exists($key, $parameters) && $parameters[$key] !== "null"
        && !is_array($parameters[$key]) && !empty(trim($parameters[$key]))
        ? trim($parameters[$key]) : null;
    }

    public static function getInt(array $parameters, string $key): ?int
    {
        return array_key_exists($key, $parameters) && !empty($parameters[$key]) && is_numeric($parameters[$key]) ? (int) $parameters[$key] : null;
    }

    public static function getFloat(array $parameters, string $key): ?float
    {
        return array_key_exists($key, $parameters) && !empty($parameters[$key]) && is_numeric($parameters[$key]) ? (float) $parameters[$key] : null;
    }

    public static function getBool(array $parameters, string $key): ?bool
    {
        return array_key_exists($key, $parameters) && isset($parameters[$key])
        ? filter_var($parameters[$key], FILTER_VALIDATE_BOOLEAN) : null;
    }

    public static function getBoolTrue(array $parameters, string $key): ?bool
    {
        return array_key_exists($key, $parameters) && isset($parameters[$key])
        ? (filter_var($parameters[$key], FILTER_VALIDATE_BOOLEAN)) ?? true : true;
    }

    public static function getBoolFalse(array $parameters, string $key): ?bool
    {
        return array_key_exists($key, $parameters) && isset($parameters[$key])
        ? (filter_var($parameters[$key], FILTER_VALIDATE_BOOLEAN) ?? false) : false;
    }

    public static function getDate(array $parameters, string $key): ?DateTime
    {
        return array_key_exists($key, $parameters) && !empty($parameters[$key]) ? new DateTime($parameters[$key]) : null;
    }

    public static function getSerializeArray(array $parameters, string $key): ?string
    {
        $value = DtoUtils::getArray($parameters, $key);
        if ($value) {
            return serialize($value);
        }
        return null;
    }

    public static function getSerializeArrayValues(array $parameters, string $key): ?string
    {
        $value = DtoUtils::getArray($parameters, $key);
        if ($value) {
            return serialize(array_values($value));
        }
        return null;
    }

    public static function getArray(array $parameters, string $key): ?array
    {
        return array_key_exists($key, $parameters) && !empty($parameters[$key]) ? $parameters[$key] : [];
    }

    public static function getArrayOrNull(array $parameters, string $key): ?array
    {
        $value = DtoUtils::getArray($parameters, $key);
        return count($value) > 0 ? $value : null;
    }
}
