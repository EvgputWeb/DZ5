<?php

require_once 'BaseModel.php';
require_once 'User.php';


class Userslist extends BaseModel
{
    public function getUsersList()
    {
        try {
            $sth = Db::getConnection()->query('SELECT * FROM users ORDER BY id');
            $usersList = $sth->fetchAll(PDO::FETCH_ASSOC);
            if ($usersList === false) {
                return 'Ошибка при выполнении запроса к БД';
            } else {
                return $usersList;
            }
        } catch (PDOException $e) {
            return 'Ошибка при запросе к БД';
        }
    }


    public function deleteUser($userId)
    {
        try {
            $res = Db::getConnection()->exec('DELETE FROM users WHERE id = '. intval($userId));
            if ($res === false) {
                return 'Ошибка при удалении';
            } else {
                // Удаляем фотку, если она есть
                $photoFilename = User::$photosFolder . '/photo_'. intval($userId) . '.jpg';
                if (file_exists($photoFilename)) {
                    unlink($photoFilename);
                }
                return true;
            }
        } catch (PDOException $e) {
            return 'Ошибка при запросе к БД';
        }
    }
}
