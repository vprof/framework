<?php

namespace Framework\Controller;

use Framework\Router\Router;
use Framework\Response\Response;
use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Response\ResponseRedirect;

class Controller {
    
    
    public function render($layout, $data = null) {
        $controller_class = get_class($this);
        $segments = explode('\\', $controller_class);
        $root_namespace = array_shift($segments);
        $path_to_pkg = Router::getPath($root_namespace);
        $ctrl = array_pop($segments);
        $view_dir_name = str_replace('Controller', '', $ctrl);
        $layout_full_path = realpath($path_to_pkg . '/views/' . $view_dir_name . '/' . $layout . '.php');
        $renderer = new Renderer();
        return new Response($renderer->render($layout_full_path, $data));
    }

    public function getRequest() {
        return Service::get('request');
    }

    public function redirect($url, $type = null, $msg = null) {
        $flushmsg = Service::get('session');
        $flushmsg->setFlush($type, $msg);
        return new ResponseRedirect($url);
    }

    public function generateRoute($name, $params = array()) {
        $router = Service::get('route');
        return $router->buildRoute($name, $params);
    }
    
    public static function executeRequest($executeController, $action, $args = array(), $resolutions = NULL) {
        if ($resolutions != NULL) {
            Service::get('security')->checkResolutions($resolutions);
        }
        $controllerInstance = new $executeController();
        if ($args === NULL) {
            $args = array();
        }
        return call_user_func_array(array($controllerInstance,$action->name) , $args);
    }
}
?>