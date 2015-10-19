<?php

namespace Framework\Router;
use Blog\Controller;

class Router {

    private $routes;
    public $controller;
    public $action;

    function __construct($routes) {

        $this->routes = $routes;
    }

    public function start($uri) {

        echo $uri . '<br>';
        $keyRoute = $this->foundRoute($uri);
        echo '<br>';
        $route = $this->routes[$keyRoute];
        $this->controller = $route['controller'];
        echo $this->controller;
        echo '<br>';
        $this->action = $route['action'];
        echo $this->action;
        echo '<br>';
        $this->checkexists();
    }

    private function foundRoute($uri) {
        foreach ($this->routes as $key => $value) {
            $pattern = $value['pattern'];
            $regExpp = $this->patternToReg($pattern);
            $r = preg_match($regExpp, $uri);
            if (1 == $r) {
                echo $key . '<br>';
                var_dump($value);
                return $key;
                break;
            }
        }
    }

    private function patternToReg($pattern) {

        return preg_replace('~\{[\w\d_]+\}~', '\d+', '~^' . $pattern . '$~');
    }

    private function checkexists() {
        $conFileName = $this->controller . '.php';
        echo $conFileName.'<br>';
        if (file_exists($conFileName)) {
            $controller = new $conFileName;
            $reflection = new ReflectionClass($controller);
            $controllers_actions = $reflection->getMethods();
            var_dump($controllers_actions);
            /*if (){
                
            }*/
        }  else {
            echo 'Cannot find controller';
        }
    }

}
