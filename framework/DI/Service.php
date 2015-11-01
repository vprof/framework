<?php

namespace Framework\DI;

use framework\Exception\FrameworkException;

class Service {

    private static $services = array();
    protected static $instance;

    final protected function __construct() {
        
    }

    final private function __clone() {
        
    }

    final public static function getInstance() {
        return (self::$instance === null) ?
                self::$instance = new self() :
                self::$instance;
    }

    public function doAction() {
        
    }

    public static function set($service, $object) {
        if (!array_key_exists($service, self::$services)) {
            self::$services[$service] = $object;
        }
    }

    public static function get($service) {
        if (array_key_exists($service, self::$services)) {
            return self::$services[$service];
        } else {
            throw new FrameworkException('Service not found - ' . $service);
        }
    }
    
    public static function destroy($service){
        
        unset(self::$services[$service]);
    }
}

?>