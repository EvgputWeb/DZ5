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

            // Тестируем параметры на корректность
            $testParamsResult = $this->testRegisterParams($userData['login'], $userData['password'], $userData['password-again']);

            if ($testParamsResult === true) {
                // Входные параметры - OK.  Обращаемся к модели пользователя - регистрируем его
                $userRegisterResult = $this->model->Register($userData, $userId);

                if ($userRegisterResult === true) {
                    setcookie('user_id', User::encryptUserId($userId), time() + User::$cookieLiveTime, '/', $_SERVER['SERVER_NAME']);
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
                setcookie('user_id', User::encryptUserId($userId), time() + User::$cookieLiveTime, '/', $_SERVER['SERVER_NAME']);
                $userInfo = User::getUserInfoById($userId);
                $this->view->render('auth_success', ['name' => $userInfo['name']]);
            } else {
                $this->view->render('auth_error', [$userAuthResult]);
            }
        }
    }




    private function testRegisterParams($login, $password, $passwordAgain)
    {
        if (!is_string($login) || !is_string($password) || !is_string($passwordAgain)) {
            return 'Параметры должны быть строковыми';
        }
        if (strlen($login) < 6) {
            return 'Логин должен содержать хотя бы 6 символов';
        }
        if (strlen($password) < 6) {
            return 'Пароль должен содержать не менее 6 символов';
        }
        if ($password != $passwordAgain) {
            return 'Пароли не совпадают';
        }
        return true;
    }
}
