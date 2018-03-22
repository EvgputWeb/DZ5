<?php

require_once 'Controller.php';
require_once APP . '/models/User.php';


class MainController extends Controller
{
    public function actionIndex()
    {
        $userInfo = User::getUserInfoByCookie();

        if ($userInfo['isLogined']) {
            $this->view->render('main', [$userInfo['name']]);
        } else {
            $this->view->render('main', []);
        }
    }
}
