<?php

require_once 'Controller.php';
require_once APP . '/models/User.php';
require_once APP . '/models/Userslist.php';


class UserslistController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Userslist();
    }

    public function actionIndex()
    {
        $userInfo = User::getUserInfoByCookie();

        if ($userInfo['isLogined']) {
            // Это авторизованный пользователь
            // Берём у модели список пользователей
            $getUsersListResult = $this->model->getUsersList($usersList);

            if ($getUsersListResult === true) {
                $this->view->render('userslist',
                    ['login' => $userInfo['login'], 'name' => $userInfo['name'], 'list' => $usersList]);
            } else {
                $this->view->render('userslist',
                    ['login' => $userInfo['login'], 'name' => $userInfo['name'], 'errorMessage' => $getUsersListResult]);
            }
        } else {
            // Пользователь не авторизован - доступ в раздел запрещён
            $this->view->render('userslist_denied', []);
        }
    }

    public function actionDeleteUser(array $params)
    {
        if (!isset($params['id'])) {
            echo json_encode(['result' => 'fail', 'errorMessage' => 'Неверный запрос'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $userInfo = User::getUserInfoByCookie();
        if ($userInfo['isLogined']) {
            // Это авторизованный пользователь - он имеет права на удаление

            if ($userInfo['id'] == $params['id']) {
                // Хочет удалить сам себя
                echo json_encode(['result' => 'fail', 'errorMessage' => 'Нельзя удалять себя'], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Запоминаем информацию об удаляемом, чтобы потом отправить её в скрипт
            $info = User::getUserInfoById($params['id']);
            if (empty($info)) {
                // Нет такого пользователя
                echo json_encode(['result' => 'fail', 'errorMessage' => 'Нет такого пользователя'], JSON_UNESCAPED_UNICODE);
                return;
            }
            // Вызываем у модели функцию удаления
            $deleteUserResult = $this->model->deleteUser($params['id']);

            if ($deleteUserResult === true) {
                echo json_encode(['result' => 'success', 'name' => $info['name'], 'login' => $info['login'] ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['result' => 'fail', 'errorMessage' => $deleteUserResult], JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Пользователь не авторизован - он не имеет прав
            echo json_encode(['result' => 'fail', 'errorMessage' => 'Вы не авторизованы. Нет прав на удаление'], JSON_UNESCAPED_UNICODE);
        }
    }


}
