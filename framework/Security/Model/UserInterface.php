<?php

namespace Framework\Security\Model;

interface UserInterface {

    public function getRole();

    public static function getTable();
}

?>