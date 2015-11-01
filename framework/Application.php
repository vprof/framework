<?php

namespace Framework;

use Framework\Router\Router;
use Framework\DI\Service;
use Framework\Response\Response;
use Framework\Renderer\Renderer;
use Framework\Database\Connect;
use Framework\Session\Session;
use Framework\Request\Request;
use Framework\Security\Security;
use Framework\Controller\Controller;
use framework\Exception\SecurityException;
use framework\Exception\AccessException;



class Application {

    private $configs;
    private $result;
    
    function __construct($config) {

        if (file_exists($config) && is_readable($config)) {
            $this->configs = require_once($config);
        }
        Service::set('config', $this->configs);
        Service::set('router', new Router($this->configs['routes']));
        $connect = new Connect($this->configs['pdo']);
        Service::set('database', $connect::getDatabase());
        Service::set('session', new Session());
        Service::set('security', new Security());
        Service::set('request', new Request());
        Service::get('security')->generateToken();
        /*if (!Service::get('security')->checkToken()) {
            die('Access denied!!!');
        }*/
        Service::set('response', new Response);
        Service::set('app', $this);
        
    }

    function run() {
        /*} catch (\Exception $e) {
            $argsForRendering['message'] = $e->getMessage();
            $argsForRendering['code'] = 500;
            if ($e instanceof HttpNotFoundException)
                $argsForRendering['code'] = 404;
            $renderer = new Renderer;
            $this->result = new Response();
            $this->result->setContent($renderer->render($this->config['error_500'], $argsForRendering));
        }
        if ($this->result instanceof Response) {
            $renderer = new Renderer();
            $session = Service::get('session');
            $flush = $session->getFlush();
            $argsForRendering['curLocale'] = $localization->getCurrentLocale();
            $argsForRendering['avalLocale'] = $localization->getAvailableLocales();
            $argsForRendering['currentURN'] = $request->getURN();
            $argsForRendering['content'] = $this->result->getContent();
            $argsForRendering['route'] = Service::get('router')->getRouteParams();
            $argsForRendering['user'] = Service::get('security') -> getUser();
            $argsForRendering['flush'] = $flush;
            $session->getAndClearFlush();
            $this->result->setContent($renderer->render($this->config['main_layout'], $argsForRendering));
        }
        $this->result->send();*/
        
        $router = Service::get('router');
        $request = Service::get('request');
        $route = $router->start(\filter_input(\INPUT_SERVER, 'REQUEST_URI', \FILTER_SANITIZE_URL));
        $controller = $router->getController();
        $method = $router->getMethod();
        $params = $router->getVars();
        $resolutions = $router->getResolutions();
        try {
            if (!(Service::get('security')->checkToken())) {
                throw new SecurityException("wrong token");
            }
            $this->result = Controller::executeRequest($controller, $method, $params, $resolutions);
        } catch (AccessException $e) {
            $request = Service::get('request');
            $session = Service::get('session');
            $session->setReturnURL($request->getURN());
            $url = $router->getRoute('login');
            $this->result = new ResponseRedirect($url);
        } catch (\Exception $e) {
            $data['message'] = $e->getMessage();
            $data['code'] = 500;
            if ($e instanceof HttpNotFoundException) {
                $data['code'] = 404;
            }
            $renderer = new Renderer;
            //$this->result = new Response();
            $this->result->setContent($renderer->render($this->configs['error_500'], $data));
        }
        $this->result = Controller::executeRequest($controller, $method, $params, $resolutions);
        
        if ($this->result instanceof Response) {
            $renderer = new Renderer();
            $session = Service::get('session');
            $flush = $session->getFlush();
            $data['currentURN'] = $request->getURN();
            $data['content'] = $this->result->getContent();
            $data['route'] = Service::get('router')->foundRoute($request->getURN());
            $data['user'] = Service::get('security')->getUser();
            $data['flush'] = $flush;            

            $this->result->setContent($renderer->render($this->configs['main_layout'], $data));
        }
        $this->result->send();
    }

}

?>