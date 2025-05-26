<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    /*
    public function add($nameUser, $password)
    {
        $sql = "INSERT INTO users (username,password) values ('$nameUser', '$password')";
        $this->database->execute($sql);
    }
    */

    public function add($nameUser, $password)
    {
        $sql = "INSERT INTO users (username, password) VALUES ('$nameUser', '$password')";
        $this->database->execute($sql);
    }

    public function existeUsuario($usuario)
    {
        $sql = "SELECT * FROM users WHERE username = '$usuario'";
        $resultado = $this->database->query($sql);

        // $resultado es un array o false, dependiendo cómo esté implementado tu método query
        // Si es array, verificamos si tiene elementos (usuario encontrado)
        return !empty($resultado);
    }
}