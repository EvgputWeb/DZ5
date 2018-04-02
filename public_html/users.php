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

// Автозагрузка
require_once ROOT . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'mvc',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();


/*
class User extends Model {
    public $timestamps = false;

}

$users = User::all();

echo '<pre>';
print_r($users->toArray());
*/