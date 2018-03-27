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
    private static $captchaSiteKey;      // ключ капчи для клиентской части
    private static $captchaSecretKey;    // ключ капчи для серверной части

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
        self::$captchaSiteKey = $cfg['captcha']['siteKey'];
        self::$captchaSecretKey = $cfg['captcha']['secretKey'];
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

    public static function getCaptchaSiteKey()
    {
        return self::$captchaSiteKey;
    }

    public static function getCaptchaSecretKey()
    {
        return self::$captchaSecretKey;
    }

}
