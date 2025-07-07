<?php

class LoginModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }


    public function getUserByUsername($username) {
        // Escapamos para prevenir inyección
        $conn = $this->db->getConnection();
        $username = $conn->real_escape_string($username);

        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$username'";
        $result = $this->db->query($sql); // devuelve array

        return $result[0] ?? null; // retornamos la primera fila o null
    }
}