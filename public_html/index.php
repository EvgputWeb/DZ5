<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT', realpath(__DIR__ . '/..'));
define('APP', ROOT . '/app');
define('PUBLIC_HTML', ROOT . '/public_html');

// FRONT CONTROLLER

// Подключение файлов ядра
require_once APP . '/core/Config.php';
require_once APP . '/core/Db.php';
require_once APP . '/core/Router.php';
require_once APP . '/models/BaseModel.php';


// Загружаем конфигурацию
Config::loadConfig();


// Установка соединения с БД
if (Db::setConnection() == false) {
    echo 'Ошибка соединения с БД';
    die;
}


// Вызор Router
$router = new Router();
$router->run();
