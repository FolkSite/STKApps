<?php

namespace Application\Core;

class Route
{

    public function start()
    {
        $get = null;
        $controllerName = 'Ads';
        $actionName = 'getPage';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if (!empty($routes[1])) {
            // делает первую блукву прописной, остальные строчными, потому что 
            // так выглядят названия соответствующих классов и файлов, например AuthModel
            $controllerName = ucfirst(strtolower($routes[1]));
        } else {
            // по умолчанию отправляет на страницу со списком объявлений
            // без изменения адреса - будут ломаться ссылки во вью
            $url = 'Location: /' . strtolower($controllerName) . '/';
            header($url);
            exit;
        }

        if (!empty($routes[2])) {
            /*
             * если передаются get переменные, то они будут отделены от названия метода
             * и переданы в качестве аргумента в его вызов
             */
            if (isset($_GET)) {
                $get = $_GET;
                // отделяет имя метода от переменных
                $routes[2] = explode('?', $routes[2]);
                $routes[2] = $routes[2][0];
            }
            $actionName = strtolower($routes[2]);
        }
        
        $controllerlClass = $controllerName . 'Controller';
        $controllerNamespace = 'Application\\Controllers\\' . $controllerlClass;
        /*
        var_dump($controllerNamespace);
        var_dump(class_exists($controllerNamespace));
        */
        if (!class_exists($controllerNamespace)) {
            $this->getErrorPage404();
        }

        $controller = new $controllerNamespace;
        $action = $actionName;

        if (method_exists($controller, $action)) {
            $controller->$action($get);
        } else {
            $this->getErrorPage404();
        }
    }

    private function getErrorPage404()
    {
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        exit;
    }
}
