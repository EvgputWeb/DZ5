<?php

require_once(APP . '/config/config.php');

class Model
{
    public static $dbh;  // коннект с базой
    public static $cookieCryptPassword; // пароль для шифрования куки
    public static $cookieLiveTime; // время жизни куки

    public static function config()
    {
        $cfg = getAppGonfig();
        self::$cookieCryptPassword = $cfg['cookieCryptPassword'];
        self::$cookieLiveTime = $cfg['cookieLiveTime'];
    }


}
