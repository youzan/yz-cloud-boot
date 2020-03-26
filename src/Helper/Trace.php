<?php


namespace YouzanCloudBoot\Helper;


use Exception;

class Trace
{

    private const KEY = 'x-cat-trace';


    public static function gen()
    {
        try {
            $appName = $_SERVER['application_name'] ?? '';
            $ip = base_convert(str_replace('.', '', $_SERVER['SERVER_ADDR'] ?? 0), 10, 16);
            return sprintf('%s-%s-%s-%s', $appName, $ip, time() * 1000, random_int(0, 999999));
        } catch (Exception $e) {
            return '';
        }
    }

    public static function key()
    {
        return self::KEY;
    }
}