<?php

namespace Framework\Security;

use Framework\DI\Service;
use Framework\Model\ActiveRecord;
use Framework\Security\Model\UserInterface;
use framework\Exception\AccessException;

class Security extends ActiveRecord implements UserInterface {

    public $role;
    protected $session;

    public function __construct() {
        $this->session = Service::get('session');
        $this->session->putParameter('isAuthenticated', false, false);
    }

    public function isAuthenticated() {
        if ($this->session->getParameter('authenticated')) {
            return $this->session->getParameter('authenticated');
        } else {
            return false;
        }
    }

    /**
     * Authorized user.
     *
     * @param $user
     */
    public function setUser($user) {
        $this->session->putParameter('authenticated', $user);
    }

    /**
     * Gets is authorized user.
     *
     * @return mixed
     */
    public function getUser() {
        return $this->session->getParameter('authenticated');
    }

    /**
     * Remove authorized session user.
     */
    public function clear() {
        $this->session->unsetSession('authenticated');
        $this->session->putParameter('isAuthenticated', false);
    }

    /**
     * Return user role.
     *
     * @return mixed
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Generates a new token, means md5.
     *
     * @return string
     */
    public function generateToken() {
        if (Service::get('session')->getParameter('token')) {
            return Service::get('session')->getParameter('token');
        } else {
            $token = md5(Service::get('session')->getID() . time());
            setcookie('token', $token);
            Service::get('session')->putParameter('token', $token, FALSE);
        }
    }

    /**
     * Checks if this token the user in the cookie.
     *
     * @return bool
     */
    public function checkToken() {
        $token = (Service::get('request')->post('token')) ? Service::get('request')->post('token') : null;
        if (!is_null($token)) {
            return ($token == $_COOKIE['token']) ? true : false;
        } else {
            return true;
        }
    }

    public function checkResolutions($resolutions = array()) {
        
        $currentResolutions = $this->session->getParameter('userRole');
        if (!in_array($currentResolutions, $resolutions)) {
            $request = Service::get('request');
            $returnURL = $request->getURN();
            throw new AccessException($returnURL);
        }
    }

}

?>