<?php

require_once 'Model.php';

class User extends Model
{
    public static $userDataFields = ['name', 'age', 'description', 'login', 'password', 'password-again'];


    public function Register($userData, &$userId)
    {
        // Проверка: вдруг такой пользователь уже есть
        try {
            $sth = self::$dbh->prepare('SELECT count(*) as count FROM users WHERE lcase(login) = lcase(:flogin)');
            $sth->execute(['flogin' => $userData['login']]);
            $count = $sth->fetchColumn();
            if ($count > 0) {
                return 'Пользователь с таким логином уже есть';
            }
        } catch (PDOException $e) {
            return 'Ошибка при запросе к БД';
        }
        // Нет такого пользователя. Создаём.
        try {
            $sql = 'INSERT INTO users(name, age, description, login, password_hash) ' .
                'VALUES (:fname, :fage, :fdescription, :flogin, :fpassword_hash)';
            $sth = self::$dbh->prepare($sql);
            $sth->execute([
                'fname' => $userData['name'],
                'fage' => intval($userData['age']),
                'fdescription' => $userData['description'],
                'flogin' => $userData['login'],
                'fpassword_hash' => password_hash($userData['password'], PASSWORD_BCRYPT)
            ]);
            // Всё получилось
            // Возвращаем true и отдаём userId (чтобы установить куку)
            $userId = self::$dbh->lastInsertId();
            return true;
        } catch (PDOException $e) {
            return 'Ошибка при добавлении пользователя в БД';
        }
    }


    public function Auth($userData, &$userId)
    {
        try {
            $sql = 'SELECT users.id,users.password_hash FROM users WHERE (lcase(login) = lcase(:flogin))';
            $sth = self::$dbh->prepare($sql);
            $sth->execute(['flogin' => $userData['login']]);
            $row = $sth->fetch();
            if ($row === false) {
                return 'Пользователь с таким логином не найден';
            } else {
                // Есть пользователь с таким логином
                // Проверяем пароль
                if (password_verify($userData['password'], $row['password_hash'])) {
                    // Успешная авторизация.
                    // Возвращаем true и отдаём userId (чтобы установить куку)
                    $userId = $row['id'];
                    return true;
                } else {
                    return 'Неверный пароль';
                }
            }
        } catch (PDOException $e) {
            return 'Ошибка при запросе к БД';
        }
    }


    public static function encryptUserId($id)
    {
        return openssl_encrypt($id, 'AES-128-ECB', self::$cookieCryptPassword);
    }

    public static function decryptUserId($cryptedId)
    {
        return openssl_decrypt($cryptedId, 'AES-128-ECB', self::$cookieCryptPassword);
    }


    public static function getUserInfoById($id)
    {
        try {
            $sth = self::$dbh->prepare('SELECT users.name,users.login FROM users WHERE id = :fid');
            $sth->execute(['fid' => $id]);
            $row = $sth->fetch();
            if ($row === false) {
                return [];
            } else {
                $userInfo = [];
                $userInfo['name'] = $row['name'];
                $userInfo['login'] = $row['login'];
                return $userInfo;
            }
        } catch (PDOException $e) {
            return [];
        }
    }


    public static function getUserInfoByCookie()
    {
        $userInfo = [];
        $userInfo['isLogined'] = false;
        if (!isset($_COOKIE['user_id'])) { // Это незалогиненный пользователь
            return $userInfo;
        }
        // Это залогиненный пользователь.
        // Возвращаем его имя и логин, которые берём из базы
        $userInfo['isLogined'] = true;

        // Расшифровываем id пользователя из куки
        $cryptedUserId = $_COOKIE['user_id'];
        $userInfo['id'] = self::decryptUserId($cryptedUserId);

        $usrInf = self::getUserInfoById($userInfo['id']);

        return array_merge($userInfo, $usrInf);
    }
}
