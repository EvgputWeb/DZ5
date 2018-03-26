<?php

require_once 'Controller.php';
require_once APP . '/models/User.php';


class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }


    public function actionRegister(array $params)
    {
        if (count($params) == 0) {
            // Нет параметров - показываем пустую форму регистрации
            $this->view->render('register', []);
        } else {
            // Есть данные пользователя, пришедшие из браузера (через POST)
            $userData = [];
            foreach (User::$userDataFields as $field) {
                $userData[$field] = isset($params[$field]) ? $params[$field] : '';
            }
            $userData['login'] = strtolower(trim($userData['login']));
            $userData['password'] = trim($userData['password']);
            $userData['password-again'] = trim($userData['password-again']);

            // Если есть фотка, то добавляем в данные пользователя имя загруженного файла
            if ((isset($_FILES)) && (isset($_FILES['photo']))) {
                $userData['photo_filename'] = $_FILES['photo']['tmp_name'];
            }

            // Тестируем параметры на корректность
            $testParamsResult = $this->testRegisterParams($userData['login'], $userData['password'], $userData['password-again']);

            if ($testParamsResult === true) {
                // Входные параметры - OK.  Обращаемся к модели пользователя - регистрируем его
                $userRegisterResult = $this->model->Register($userData, $userId);

                if ($userRegisterResult === true) {
                    setcookie('user_id', User::encryptUserId($userId), time() + Config::getCookieLiveTime(), '/', $_SERVER['SERVER_NAME']);
                    $this->view->render('register_success', ['login' => $userData['login']]);
                } else {
                    $this->view->render('register_error', [$userRegisterResult]);
                }
            } else {
                $this->view->render('register_error', [$testParamsResult]);
            }
        }
    }


    public function actionAuth(array $params)
    {
        if (count($params) == 0) {
            // Нет параметров - показываем пустую форму авторизации
            $this->view->render('auth', []);
        } else {
            // Есть данные пользователя, пришедшие из браузера (через POST)
            $userData['login'] = isset($params['login']) ? strtolower(trim($params['login'])) : '';
            $userData['password'] = isset($params['password']) ? trim($params['password']) : '';

            // Обращаемся к модели пользователя - авторизуем его
            $userAuthResult = $this->model->Auth($userData, $userId);

            if ($userAuthResult === true) {
                setcookie('user_id', User::encryptUserId($userId), time() + Config::getCookieLiveTime(), '/', $_SERVER['SERVER_NAME']);
                $userInfo = User::getUserInfoById($userId);
                $this->view->render('auth_success', ['name' => $userInfo['name']]);
            } else {
                $this->view->render('auth_error', [$userAuthResult]);
            }
        }
    }


    public function actionPhoto(array $params)
    {
        if (empty($params['request_from_url'])) {
            return;
        }
        $userInfo = User::getUserInfoByCookie();
        if (!$userInfo['isLogined']) {
            // Не авторизованному - не отдаём
            header('HTTP/1.0 403 Forbidden');
            echo 'You are not authorised user!';
            return;
        }
        // Нужно отдать картинку
        $photoFilename = Config::getPhotosFolder() . '/photo_' . $params['request_from_url'] . '.jpg';
        if (!file_exists($photoFilename)) {
            // отдаём пустую картинку 1x1 пиксель
            $photoFilename = Config::getPhotosFolder() . '/empty.jpg';
        }

        header("Content-Type: image/jpeg");
        header("Content-Length: " . filesize($photoFilename));
        echo file_get_contents($photoFilename);
    }


    private function testRegisterParams($login, $password, $passwordAgain)
    {
        if (!is_string($login) || !is_string($password) || !is_string($passwordAgain)) {
            return 'Параметры должны быть строковыми';
        }
        if ((strlen($login) < Config::getMinLoginLength()) || (strlen($login) > Config::getMaxLoginLength())) {
            return 'Логин должен содержать от ' . Config::getMinLoginLength() .
                ' до ' . Config::getMaxLoginLength() . ' символов';
        }
        if ((strlen($password) < Config::getMinPasswordLength()) || (strlen($password) > Config::getMaxPasswordLength())) {
            return 'Пароль должен содержать от ' . Config::getMinPasswordLength() .
                ' до ' . Config::getMaxPasswordLength() . ' символов';
        }
        if (!preg_match('/^[a-z0-9_-]{1,}$/', $login)) {
            return 'Логин должен состоять из строчных латинских букв, цифр, символов подчеркивания и дефиса';
        }
        if ($password != $passwordAgain) {
            return 'Пароли не совпадают';
        }
        return true;
    }
}
