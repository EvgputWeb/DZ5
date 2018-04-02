<?php

use Illuminate\Database\Eloquent\Model;


class User extends Model {

    public $timestamps = false;

    public static $userDataFields = ['name', 'age', 'description', 'email', 'login', 'password', 'password-again'];


    public function Register($userData, &$userId)
    {
        // Проверка: вдруг такой пользователь уже есть
        try {
            $sth = Db::getConnection()->prepare('SELECT count(*) as count FROM users WHERE lcase(login) = lcase(:flogin)');
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
            $sth = Db::getConnection()->prepare($sql);
            $sth->execute([
                'fname' => $userData['name'],
                'fage' => intval($userData['age']),
                'fdescription' => $userData['description'],
                'flogin' => $userData['login'],
                'fpassword_hash' => password_hash($userData['password'], PASSWORD_BCRYPT)
            ]);
            // Всё получилось
            // Возвращаем true и отдаём userId (чтобы установить куку)
            $userId = Db::getConnection()->lastInsertId();
            // Если есть фотка, то помещаем её в папку для фоток с именем "photo_".$userId."jpg"
            if (isset($userData['photo_filename'])) {
                $this->saveUserPhoto($userId, $userData['photo_filename']);
            }
            return true;
        } catch (PDOException $e) {
            return 'Ошибка при добавлении пользователя в БД';
        }
    }


    public function Auth($userData, &$userId)
    {
        $user = $this->whereRaw('lcase(login) = ?', strtolower($userData['login']))->get(['id','password_hash'])->toArray();
        if (empty($user)) {
            return 'Пользователь с таким логином не найден';
        }
        // Есть пользователь с таким логином
        // Проверяем пароль
        if (password_verify($userData['password'], $user[0]['password_hash'])) {
            // Успешная авторизация.
            // Возвращаем true и отдаём userId (чтобы установить куку)
            $userId = $user[0]['id'];

            echo 'USER ID = '.$userId;
            die;

            return true;
        } else {
            return 'Неверный пароль';
        }



        /*try {
            $sql = 'SELECT users.id,users.password_hash FROM users WHERE (lcase(login) = lcase(:flogin))';
            $sth = Db::getConnection()->prepare($sql);
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
        }*/
    }


    public static function encryptUserId($id)
    {
        return openssl_encrypt($id, 'AES-128-ECB', Config::getCookieCryptPassword());
    }

    public static function decryptUserId($cryptedId)
    {
        return openssl_decrypt($cryptedId, 'AES-128-ECB', Config::getCookieCryptPassword());
    }


    public static function getUserInfoById($id)
    {
        try {
            $sth = Db::getConnection()->prepare('SELECT users.name,users.login FROM users WHERE id = :fid');
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

        if (empty($usrInf)) {
            // Упс... А пользователя такого нету...
            $userInfo = [];
            $userInfo['isLogined'] = false;
            return $userInfo;
        }

        return array_merge($userInfo, $usrInf);
    }


    private function saveUserPhoto($userId, $tmpFilename)
    {
        if (empty($tmpFilename)) {
            return;
        }
        $imgTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
        $imgType = exif_imagetype($tmpFilename);
        if (!in_array($imgType, $imgTypes)) {
            // недопустимый тип файла
            return;
        }
        switch ($imgType) {
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($tmpFilename);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($tmpFilename);
                break;
        }
        if ($img === false) {
            return;
        }
        // обрезаем картинку - делаем квадрат
        $size = min(imagesx($img), imagesy($img));
        $img2 = imagecrop($img, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($img2 === false) {
            imagedestroy($img);
            return;
        }
        imagedestroy($img);
        // масштабируем до размера 100x100
        $imageScaled = imagescale($img2, 100);
        if ($imageScaled === false) {
            imagedestroy($img2);
            return;
        }

        // Сохраняем в папку с фотками пользователей
        $photoFilename = Config::getPhotosFolder() . '/photo_' . intval($userId) . '.jpg';
        imagejpeg($imageScaled, $photoFilename, 90);
        imagedestroy($imageScaled);

        // Удаляем временный файл
        unlink($tmpFilename);
    }
}
