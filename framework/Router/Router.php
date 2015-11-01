<?php

namespace Framework\Router;

class Router {

    private $routes;
    private $resolutions;
    private $vars;
    private $method;
    private $controller;
    //private $routeParams;

    function __construct($routes) {

        $this->routes = $routes;
    }

    public function start($uri) {

        $keyRoute = $this->foundRoute($uri);
        $route = $this->routes[$keyRoute];
        if (!$this->checkexists($route, $uri)) {
            $route = $this->routes["home"];
        }
        if (array_key_exists('security', $route)) {
            $this->resolutions = $route['security'];
        }
        
        /*foreach ( $this->routes as $key => $rout){
            $pattern = $this->getRegexpByRout($rout);
            preg_match($pattern, $uri,$rez);
            if ((!empty($rez))&&($uri == $rez[0])) {
                $controller = '\\'.$rout['controller'];
                $action = $rout['action'];
                $action = $action.'Action';
                $this->routeParams['_name'] = $key;
                if (array_key_exists('security', $rout))
                    $grants = $rout['security'];
                break;
            }
        }*/

        return $route;
    }
    
     /*private function getRegexpByRout($rout) {
        $pattern = $rout['pattern'];
        $pattern = preg_replace('/\//', '\/', $pattern);
        preg_match_all('/{(.*)}/U' ,$pattern, $placeholders);
        $placeholdersCount = count($placeholders[0]);
        for ($i = 0; $i < $placeholdersCount; $i++) {
            $changeTo = $rout['_requirements'][$placeholders[1][$i]];
            $change = $placeholders[0][$i];
            $pattern = preg_replace('/'.$change.'/', '('.$changeTo.')', $pattern);
        }
        return '/'.$pattern.'/';
    }    */

     public function foundRoute($uri) {

        foreach ($this->routes as $key => $value) {
            $pattern = $value['pattern'];
            $regExpp = $this->patternToReg($pattern);
            $r = preg_match($regExpp, $uri);
            if (1 == $r) {
                return $key;
            }
        }
    }

    private function patternToReg($pattern) {

        return preg_replace('~\{[\w\d_]+\}~', '\d+', '~^' . $pattern . '$~');
    }

    function getParameters($route, $uri) {

        $vars = array();
        if (array_key_exists('_requirements', $route)) {
            $params = $route['_requirements'];
            foreach ($params as $key => $value) {
                $isExp = preg_match('/' . $value . '/', $uri);
                if (1 == $isExp) {
                    preg_match('/' . $value . '/', $uri, $subject);
                    $vars[$key] = $subject[0];
                } else {
                    $vars[$key] = $value;
                }
            }
        }

        return $vars;
    }

    private function checkexists($route, $uri) {
        $conFileName = str_replace('\\', DIRECTORY_SEPARATOR, __DIR__ . '\\..\\..\\src\\' . $route['controller'] . '.php');
        $RealConFileName = realpath($conFileName);
        if (file_exists($RealConFileName)) {
            $controller = new $route['controller'];
            $this->controller = $controller;
            $reflection = new \ReflectionClass($route['controller']);
            $action = $route['action'] . 'Action';
            if ($reflection->hasMethod($action)) {
                $method = new \ReflectionMethod($route['controller'], $action);
                $this->method = $method;
                $params = $method->getParameters();
                if (empty($params)) {
                    $this->vars = NULL;
                } else {
                    $this->vars = $this->getParameters($route, $uri);
                }
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function getPath($root_namespace) {
        if (!empty($root_namespace)) {
            $root_namespace = trim($root_namespace, '\\');
            $root = realpath(__DIR__ . '/../../src/' . $root_namespace);
            return $root;
        }
    }

    public function getController() {
        return $this->controller;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getVars() {
        return $this->vars;
    }

    public function getResolutions() {
        return $this->resolutions;
    }

}

?>