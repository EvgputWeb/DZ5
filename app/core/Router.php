<?php

class Router
{
    public function run()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // Значения по умолчанию
        $controllerName = 'MainController';
        $actionName = 'actionIndex';

        // Получаем контроллер
        if (!empty($routes[1])) {
            $controllerName = ucfirst(strtolower($routes[1])) . 'Controller';
        }

        // Получаем действие
        if (!empty($routes[2])) {
            $actionName = 'action' . ucfirst(strtolower($routes[2]));
        }

        // Если контроллер и действие найдены, то вызываем действие,
        // иначе - сообщение об ошибке

        $error = false;
        $filename = APP . '/controllers/' . $controllerName . '.php';

        if (file_exists($filename)) {
            require_once $filename;

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $actionName)) {
                    $actionParams = $_POST;
                    unset($_POST);
                    if (!empty($routes[3])) {
                        $actionParams['request_from_url'] = $routes[3];
                    }
                    // Вызываем действие и передаем параметры из _POST
                    $controller->$actionName($actionParams);
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }

        if ($error) {
            require_once APP . '/controllers/ErrorController.php';
            return;
        }
    }
}
