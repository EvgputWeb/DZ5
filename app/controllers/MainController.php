<?php

require_once 'Controller.php';


class MainController extends Controller
{
    public function actionIndex()
    {
        if (isset($_COOKIE['user_id'])) {
            $this->view->render('main', [$_COOKIE['user_id']]);
        } else {
            $this->view->render('main', []);
        }
    }
}
