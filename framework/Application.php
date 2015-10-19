<?php

namespace Framework;

use Framework\Router\Router;

class Application {

    private $configs;

    function __construct($config) {

        $this->configs = require_once($config);
        
    }

    function run() {
                
        $router = new Router($this->configs['routes']);
        $route = $router->start($_SERVER['REQUEST_URI']);
        
        
    }

}
