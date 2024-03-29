<?php

namespace Core;

class Params
{
    /**
     * Keep all the parameters
     *
     * @var array
     */
    private static array $params = array();

    /**
     * Get parameter value
     *
     * @param string $key
     * @return string
     */
    public static function get(string $key): ?string
	{
        if (array_key_exists($key, self::$params)) {
            return self::$params[$key];
        } else {
            return null;
        }
    }

    /**
     * Set paramters key & value
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function set(string $key, string $value): void
	{
        self::$params[$key] = $value;
    }

    /**
     * Get the parameter array
     *
     * @return array
     */
    public static function all(): array
	{
        return self::$params;
    }
}