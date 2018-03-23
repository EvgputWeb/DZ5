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



}
