<?php

require_once 'Model.php';
require_once 'User.php';


class Fileslist extends Model
{
    public function getFilesList(&$filesList)
    {
        try {
            $sth = self::$dbh->query('SELECT users.id FROM users ORDER BY id');
            $idsList = $sth->fetchAll(PDO::FETCH_COLUMN);
            if ($idsList === false) {
                return 'Ошибка при выполнении запроса к БД';
            } else {
                $filesList = [];
                for ($i = 0; $i < count($idsList); $i++) {
                    $photoFilename = User::$photosFolder . '/photo_' . intval($idsList[$i]) . '.jpg';
                    if (file_exists($photoFilename)) {
                        $filesList[$idsList[$i]] = 'photo_' . intval($idsList[$i]) . '.jpg';
                    }
                }
                return true;
            }
        } catch (PDOException $e) {
            return 'Ошибка при запросе к БД';
        }
    }


    public function deletePhoto($userId)
    {
        // Удаляем фотку, если она есть
        $photoFilename = User::$photosFolder . '/photo_' . intval($userId) . '.jpg';
        if (file_exists($photoFilename)) {
            if (unlink($photoFilename)) {
                return true;
            } else {
                return 'Ошибка при удалении файла';
            }
        } else {
            return 'Файл не найден';
        }
    }
}
