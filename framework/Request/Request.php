<?php

namespace Framework\Request;

class Request {

    private $cookies;
    private $method;
    private $host;
    private $url;
    private $urn;
    private $uri;
    private $script;
    private $params;
    private $ajax;

    public function __construct() {
        $this->cookies = $_COOKIE;
        $this->method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_URL);
        $this->host = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL);
        $this->url = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
        $this->urn = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $this->uri = $this->url . $this->urn;
        $this->script = filter_input(INPUT_SERVER, 'SCRIPT_NAME', FILTER_SANITIZE_URL);
        $this->params = $_REQUEST;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getHost() {
        return $this->host;
    }

    public function getURL() {
        return $this->url;
    }

    public function getURN() {
        return $this->urn;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getScript() {
        return $this->script;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    public function isGet() {
        if ($this->method == 'GET') {
            return true;
        } else {
            return false;
        }
    }

    public function isPost() {
        if ($this->method == 'POST') {
            return true;
        } else {
            return false;
        }
    }

    public function isAjax() {
        if ($this->ajax !== null) {
            return $this->ajax;
        }
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return $this->ajax = true;
        } else {
            return $this->ajax = false;
        }
    }

    public function post($parameterName) {
        return $this->getParameter($parameterName);
    }

    public function get($parameterName) {
        return $this->getParameter($parameterName);
    }

    private function getParameter($parameterName) {
        if ($this->parameterExist($parameterName)) {
            $rawValue = $this->params[$parameterName];
            return $this->filterValue($rawValue);
        } else {
            return null;
        }
    }

    public function filter($method) {
        if (strlen($method)) {
            $result = trim($method);
            $filterResult = preg_replace('/<\s*\/*\s*\w*>|[\$`~#<>\[\]\{\}\\\*\^%]/', "", $result);
        } else {
            return false;
        }
        return $filterResult;
    }
    
    public function parameterExist($parameterName) {
        $result = array_key_exists($parameterName, $this->params);
        return $result;
    }

}

?>