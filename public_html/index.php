<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT', realpath(__DIR__ . '/..'));
define('APP', ROOT . '/app');
define('PUBLIC_HTML', ROOT . '/public_html');

// FRONT CONTROLLER

// Подключение файлов ядра
require_once APP . '/core/Router.php';
require_once APP . '/core/Db.php';
require_once APP . '/models/Model.php';


// Установка соединения с БД
$dbh = Db::connection();
if ($dbh == null) {
    echo 'Ошибка соединения с БД';
    die;
}
Model::$dbh = $dbh;
Model::config(); // Там инициализируем пароль для шифрования куки и время жизни куки


// Вызор Router
$router = new Router();
$router->run();
