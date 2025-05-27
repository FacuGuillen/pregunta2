<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function getUserByUsername($username) {
        // Escapamos para prevenir inyección
        $conn = $this->database->getConnection();
        $username = $conn->real_escape_string($username);

        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$username'";
        $result = $this->database->query($sql); // devuelve array

        return $result[0] ?? null; // retornamos la primera fila o null
    }
}