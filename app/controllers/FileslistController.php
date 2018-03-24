<?php

require_once 'Controller.php';
require_once APP . '/models/User.php';
require_once APP . '/models/Fileslist.php';

class FileslistController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Fileslist();
    }

    public function actionIndex()
    {
        $userInfo = User::getUserInfoByCookie();

        if ($userInfo['isLogined']) {
            // Это авторизованный пользователь
            // Берём у модели список файлов
            $filesList = $this->model->getFilesList();

            if (is_array($filesList)) {
                $this->view->render('fileslist',
                    ['login' => $userInfo['login'], 'name' => $userInfo['name'], 'list' => $filesList]);
            } else {
                $errorMessage = (string)$filesList;
                $this->view->render('fileslist',
                    ['login' => $userInfo['login'], 'name' => $userInfo['name'], 'errorMessage' => $errorMessage]);
            }
        } else {
            // Пользователь не авторизован - доступ в раздел запрещён
            $this->view->render('fileslist_denied', []);
        }
    }

    public function actionDeletePhoto(array $params)
    {
        if (!isset($params['id'])) {
            echo json_encode(['result' => 'fail', 'errorMessage' => 'Неверный запрос'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $userInfo = User::getUserInfoByCookie();
        if ($userInfo['isLogined']) {
            // Это авторизованный пользователь - он имеет права на удаление
            // Вызываем у модели функцию удаления
            $deletePhotoResult = $this->model->deletePhoto($params['id']);

            if ($deletePhotoResult === true) {
                echo json_encode(['result' => 'success'], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['result' => 'fail', 'errorMessage' => $deletePhotoResult], JSON_UNESCAPED_UNICODE);
            }
        } else {
            // Пользователь не авторизован - он не имеет прав
            echo json_encode(['result' => 'fail', 'errorMessage' => 'Вы не авторизованы. Нет прав на удаление'], JSON_UNESCAPED_UNICODE);
        }
    }


}

