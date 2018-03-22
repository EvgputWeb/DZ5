<?php

require_once(APP . '/config/config.php');

class Db
{
    public static function connection()
    {
        $params = getAppGonfig();
        $db = $params['db'];
        try {
            // data source name
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            // Подключаемся к базе
            $dbh = new PDO($dsn, $db['user'], $db['password'], $opt);
            // Всё нормально - отдаём $dbh
            return $dbh;
        } catch (PDOException $e) {
            return null;
        }
    }
}
