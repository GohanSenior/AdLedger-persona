<?php

class Config
{
    private static $config = null;
    public static function get($section, $key)
    {
        if (self::$config === null) {
            self::$config = parse_ini_file(__DIR__ . '/../config/config.ini', true);
        }
        return self::$config[$section][$key] ?? null;
    }
}
