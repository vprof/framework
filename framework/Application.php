<?php

namespace Framework;

class Application {

    private $config;

    function __construct($config) {

        $this->config = require_once($config);
        
    }

    function run() {
        $nRouter = $this->router();
        $this->front($nRouter, $this->config['routes']);
    }
    
    function router(){
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        if (!empty($uri[2])){
            $uName = $uri[2];
        }  else {
            $uName = 'home';
        }
        return $uName;
    }


    function front ($router, $routes){
        echo $router.'<br>';
        var_dump($routes);
        if(array_key_exists($router, $routes)){
            $controller = $routes[$router]['controller'];
            print_r($controller);
            echo '<br>';
            $action = $routes[$router]['action'];
            print_r($action);
        }
    }
}