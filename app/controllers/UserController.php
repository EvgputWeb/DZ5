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
            // Есть параметры
            $login = isset($params['login']) ? $params['login'] : '';
            $password = isset($params['password']) ? $params['password'] : '';
            $passwordAgain = isset($params['password-again']) ? $params['password-again'] : '';

            // Тестируем параметры на корректность
            $testParamsResult = $this->testParams($login, $password, $passwordAgain);

            if ($testParamsResult === true) {
                // Входные параметры - OK.  Обращаемся к модели пользователя
                $userRegisterResult = $this->model->Register($login, $password, $userId);

                if ($userRegisterResult === true) {
                    setcookie('user_id', $userId , time() + 60, '/',  $_SERVER['SERVER_NAME']) ;
                    $this->view->render('register_success', ['login' => $login]);
                } else {
                    $this->view->render('register_error', [$userRegisterResult]);
                }
            } else {
                $this->view->render('register_error', [$testParamsResult]);
            }
        }
    }


    public function actionAuth()
    {
    }


    private function testParams($login, $password, $passwordAgain)
    {
        if (!is_string($login) || !is_string($password) || !is_string($passwordAgain)) {
            return 'Параметры должны быть строковыми';
        }
        if (strlen($login) < 6) {
            return 'Логин должен содержать хотя бы 6 символов';
        }
        if ($password != $passwordAgain) {
            return 'Пароли не совпадают';
        }
        return true;
    }
}
