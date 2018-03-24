<?php

require_once(APP . '/config/config.php');

abstract class Config {

    private static $cookieCryptPassword; // пароль для шифрования куки
    private static $cookieLiveTime; // время жизни куки
    private static $photosFolder; // папка для фоток пользователей

    public static function loadConfig()
    {
        $cfg = getAppConfig();
        self::$cookieCryptPassword = $cfg['cookieCryptPassword'];
        self::$cookieLiveTime = $cfg['cookieLiveTime'];
        self::$photosFolder = $cfg['photosFolder'];
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
