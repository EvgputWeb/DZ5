<?php

require_once 'Model.php';

class Userslist extends Model
{

    public function getUsersList(&$usersList)
    {
        try {
            $sth = self::$dbh->query('SELECT * FROM users ORDER BY id');
            $usersList = $sth->fetchAll(PDO::FETCH_ASSOC);
            if ($usersList === false) {
                return 'Ошибка при выполнении запроса к БД';
            } else {
                return true;
            }
        } catch (PDOException $e) {
            return 'Ошибка при запросе к БД';
        }
    }


}