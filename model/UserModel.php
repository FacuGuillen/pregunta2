<?php
class UserModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getUser($username){
        return $this->database->query("SELECT * FROM usuarios WHERE nombre = '$username'");
    }
}