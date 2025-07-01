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
        $foto_perfil = $db->real_escape_string($data['photo']);
        $tipo_usuario = isset($data['tipo_usuario']) ? intval($data['tipo_usuario']) : 1;  // Por si no viene, poner 1 por defecto
        $residencia = $db->real_escape_string($data['residencia']);

        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, fecha_nacimiento, email, contrasena, nombre_usuario, foto_perfil, tipo_usuario, residencia)
            VALUES ('$nombre', '$apellido', '$sexo', '$fecha_nacimiento', '$email', '$contrasena', '$nombre_usuario', '$foto_perfil', '$tipo_usuario', '$residencia')";

        if (!$db->query($sql)) {
            return $db->error;
        }

        return true;
    }

    public function insertarResidencia($data){
        $db = $this->database->getConnection();

        $ciudad = $db->real_escape_string($data['ciudad']);
        $pais = $db->real_escape_string($data['pais']);
        $latitud = $db->real_escape_string($data['latitud']);
        $longitud = $db->real_escape_string($data['longitud']);

        $sql = "INSERT INTO residencia (ciudad, pais, latitud, longitud)
        values('$ciudad', '$pais', '$latitud', '$longitud')";

        if (!$db->query($sql)) {
            return $db->error;
        }

        return $db->insert_id; // ← Esto es lo que necesitás
    }

    public function existeUsuario($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
        $resultado = $this->database->query($sql);

        // $resultado es un array o false, dependiendo cómo esté implementado tu métodoquery
        // Si es array, verificamos si tiene elementos (usuario encontrado)
        return !empty($resultado);
    }



}