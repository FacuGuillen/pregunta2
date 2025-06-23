<?php

class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function createUser($data)
    {
        $db = $this->database->getConnection();

        $nombre = $db->real_escape_string($data['name']);
        $apellido = $db->real_escape_string($data['lastname']);
        $sexo = $db->real_escape_string($data['sex']);
        $fecha_nacimiento = $db->real_escape_string($data['date']);
        $email = $db->real_escape_string($data['email']);
        $contrasena = $db->real_escape_string($data['password']);
        $nombre_usuario = $db->real_escape_string($data['nameuser']);

        /*guardar imagen*/
        $foto_perfil = $db->real_escape_string($data['photo']);

        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, fecha_nacimiento, email, contrasena, nombre_usuario, foto_perfil)
            VALUES ('$nombre', '$apellido', '$sexo', '$fecha_nacimiento', '$email', '$contrasena', '$nombre_usuario', '$foto_perfil')";

        if (!$db->query($sql)) {
            return $db->error;
        }

        return true;
    }



}