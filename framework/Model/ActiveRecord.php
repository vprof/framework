<?php

namespace Framework\Model;

use Framework\DI\Service;
use Framework\Exception\DatabaseException;

class ActiveRecord {

    protected $db;

    public function __construct() {
        $this->db = Service::get('database');
    }

    static public function find($id) {
        $db = Service::get('database');
        if ($id == 'all') {
            $query = 'SELECT * FROM ' . static::getTable();
        } else {
            $query = 'SELECT * FROM ' . static::getTable() . ' WHERE id = :id';
        }
        $statement = $db->prepare($query);
        $statement->execute([':id' => $id]);
        $result = ($id == 'all') ? $statement->fetchAll(\PDO::FETCH_OBJ) : $statement->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    static public function findByEmail($email) {
        $db = Service::get('database');
        $query = 'SELECT * FROM ' . static::getTable() . ' WHERE email = :email';
        $statement = $db->prepare($query);
        $statement->execute([':email' => $email]);
        $result = $statement->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    public function save() {
        $db = Service::get('database');
        if (!isset($this->name)) {
            $this->name = 'Guest';
        }
        if (static::getTable() == 'users') {
            $query = 'SELECT * FROM ' . static::getTable() . ' WHERE email = :email';
            $statement = $db->prepare($query);
            $statement->execute([':email' => Service::get('request')->post('email')]);
            $result = $statement->fetch(\PDO::FETCH_OBJ);
            if (is_object($result)) {
                $users_email = get_object_vars($result);
            } else {
                $users_email = null;
            }
            if (!empty($users_email['email'] == Service::get('request')->post('email'))) {
                $msg = 'This email is already registered. Please login!';
                $fsg = Service::get('session');
                $fsg->setFlush('warning', $msg);
            } else {
                $query = 'INSERT INTO ' . static::getTable() . '(email, password, role, name) VALUES (:email, :password, :role, :name)';
                $statement = $db->prepare($query);
                if ($statement->execute([':email' => $this->email, ':password' => $this->password, ':role' => $this->role, ':name' => $this->name])) {
                    $msg = 'New user registered successfully!';
                    $fsg = Service::get('session');
                    $fsg->setFlush('success', $msg);
                } else {
                    throw new DatabaseException('SQL BAD REQUEST');
                }
            }
        }
        if (static::getTable() == 'posts') {
            $query = 'INSERT INTO ' . static::getTable() . '(title, content, date) VALUES (:title, :content, :date)';
            $statement = $db->prepare($query);
            $statement->execute([':title' => $this->title, ':content' => $this->content, ':date' => $this->date]);
        }
    }

    static public function edit($id) {
        //TODO: Повторение контроллера! :(
        $title = Service::get('request')->post('title');
        $content = Service::get('request')->post('content');
        $datetime = new \DateTime();
        $date = $datetime->format('Y-m-d H:i:s');
        $db = Service::get('database');
        $query = 'UPDATE ' . static::getTable() . ' SET title = :title, content = :content, date = :date WHERE id = ' . $id;
        $statement = $db->prepare($query);
        $statement->execute([':title' => $title, ':content' => $content, ':date' => $date]);
    }

    static public function delete($id) {
        $db = Service::get('database');
        $query = 'DELETE FROM ' . static::getTable() . ' WHERE id = ?';
        $statement = $db->prepare($query);
        $statement->execute(array($id));
        return true;
    }
    
    static public function getTable() {
        //...
    }
    
    public function getRules() {
        return array();
    }
}
?>