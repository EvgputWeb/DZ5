<?php

require_once(APP . '/config/config.php');

abstract class Config
{
    private static $minLoginLength;    // минимальное кол-во символов в логине
    private static $maxLoginLength;    // максимальное кол-во символов в логине
    private static $minPasswordLength; // минимальное кол-во символов в пароле
    private static $maxPasswordLength; // максимальное кол-во символов в пароле
    private static $cookieCryptPassword; // пароль для шифрования куки
    private static $cookieLiveTime;      // время жизни куки
    private static $photosFolder;        // папка для фоток пользователей

    public static function loadConfig()
    {
        $cfg = getAppConfig();
        self::$minLoginLength = $cfg['user']['minLoginLength'];
        self::$maxLoginLength = $cfg['user']['maxLoginLength'];
        self::$minPasswordLength = $cfg['user']['minPasswordLength'];
        self::$maxPasswordLength = $cfg['user']['maxPasswordLength'];
        self::$cookieCryptPassword = $cfg['cookieCryptPassword'];
        self::$cookieLiveTime = $cfg['cookieLiveTime'];
        self::$photosFolder = $cfg['photosFolder'];
    }

    public static function getMinLoginLength()
    {
        return self::$minLoginLength;
    }

    public static function getMaxLoginLength()
    {
        return self::$maxLoginLength;
    }

    public static function getMinPasswordLength()
    {
        return self::$minPasswordLength;
    }

    public static function getMaxPasswordLength()
    {
        return self::$maxPasswordLength;
    }

    public static function getCookieCryptPassword()
    {
        return self::$cookieCryptPassword;
    }

    public static function getCookieLiveTime()
    {
        return self::$cookieLiveTime;
    }

    public static function getPhotosFolder()
    {
        return self::$photosFolder;
    }
}
