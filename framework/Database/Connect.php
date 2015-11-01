<?php

namespace framework\Database;

class Connect {

    static private $db;

    public function __construct($params) {
        $dsn = $params['dns'];
        $username = $params['user'];
        $passwd = $params['password'];
        try {
            self::$db = new \PDO($dsn, $username, $passwd);
            register_shutdown_function([$this, 'closeConnection']);
        } catch (\PDOException $e) {
            die('SQL CONNECTION ERROR: ' . $e->getMessage());
        }
    }

    static public function getDatabase() {
        return self::$db;
    }

    static public function closeConnection() {
        self::$db = NULL;
    }

}

?>