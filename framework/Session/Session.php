<?php

namespace framework\Session;

class Session {

    public $returnUrl = null;

    public function __construct() {
        $this->start();
        if (isset($_SESSION['returnUrl'])) {
            $this->returnUrl = $_SESSION['returnUrl'];
        }
    }
    
    public function start() {
        session_set_cookie_params(ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'));
        session_name(ini_get('session.name'));
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->putParameter('flush', array(), false);
    }
    
    public function destroy() {
        session_destroy();
    }
    
    public function putParameter($parameter, $value, $rewrite = true) {
        if ($this->hasParameter($parameter)) {
            if ($rewrite) {
                $_SESSION[$parameter] = $value;
            }
        } else {
            $_SESSION[$parameter] = $value;
        }
    }
    
    public function removeParameter ($parameter) {
        if ($this->hasParameter($parameter)) {
            unset($_SESSION[$parameter]);
        }
    }
    
    public function hasParameter($parameter) {
        return array_key_exists($parameter, $_SESSION);
    }
    
    public function getParameter ($parameter) {
        if ($this->hasParameter($parameter)) {
            return $_SESSION[$parameter];
        }
        return null;

    }
    
    public function getReturnUrl()
    {
        $this->returnUrl = $this->getParameter('returnURL');
        return $this->returnUrl;
    }
    
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
        $this->putParameter('returnURL', $returnUrl);
    }
    
    public function setFlush($type, $msg) {
        if (isset($msg)) {
            $session = $this->get('flush');
            $session[$type][] = $msg;
            $this->set('flush', $session);
        }
    }

    public function getFlush() {
        $flushMsg = $this->getParameter('flush');
        if ($flushMsg === NULL) {
            $flushMsg = array();
        } else {
            $this->removeParameter('flush');
        }
        return $flushMsg;
    }

    
    static public function getId() {
        return session_id();
    }

    static public function getName() {
        return session_name();
    }
    
    public function unsetSession($name) {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
            return true;
        }
        return false;
    }
}