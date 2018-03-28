<?php

abstract class Db
{
    private static $dbh = null;  // коннект с базой

    public static function getConnection()
    {
        return self::$dbh;
    }

    public static function setConnection()
    {
        $cfg = require(APP . '/config/config.php');
        $db = $cfg['db'];
        try {
            // data source name
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            // Подключаемся к базе
            self::$dbh = new PDO($dsn, $db['user'], $db['password'], $opt);
            return true;
        } catch (PDOException $e) {
            self::$dbh = null;
            return false;
        }
    }
}
