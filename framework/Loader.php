<?php

class Loader {

    private static $classPaths = array();
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

    public function registerAutoloader() {

        spl_autoload_register(array("\\Loader", "autoload"));

        self::addNamespacePath("Framework", __DIR__);
    }

    static public function addNamespacePath($namespace, $classPath) {

        self::$classPaths[trim($namespace, '\\')] = realpath($classPath);
    }

    static public function autoload($classname) {

        if ($classname[0] == '\\') {
            $classname = substr($classname, 1);
        }

        $classPaths = explode("\\", $classname);

        $resolvePath = array();
        $actualClass = array_pop($classPaths);

        do {
            $classPath = implode("\\", $classPaths);

            if (array_key_exists($classPath, self::$classPaths)) {
                if (self::$classPaths[$classPath]) {
                    array_push($resolvePath, $actualClass);
                    self::loadClass($classPath, implode(DIRECTORY_SEPARATOR, $resolvePath));
                    return;
                }
            }
            array_unshift($resolvePath, array_pop($classPaths));
        } while (count($classPaths) > 0);
    }

    static private function loadClass($namespace, $class) {

        $fullClassPath = self::$classPaths[$namespace] . DIRECTORY_SEPARATOR . $class . ".php";


        if (file_exists($fullClassPath)) {
            include_once $fullClassPath;
        }
    }

}

$loader = Loader::getInstance();
$loader->registerAutoloader();
?>