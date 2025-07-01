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

        /*generar numero random*/
        $numeroRandom = rand(10000, 99999);
        $estado = false;

        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, fecha_nacimiento, email, contrasena, nombre_usuario, foto_perfil,numero_random,estado)
            VALUES ('$nombre', '$apellido', '$sexo', '$fecha_nacimiento', '$email', '$contrasena', '$nombre_usuario', '$foto_perfil', '$numeroRandom', '$estado')";

        if (!$db->query($sql)) {
            return $db->error;
        }
        $id = $db->insert_id;

        return [
            "id_usuario" => $id,
            "nombre_usuario" => $nombre_usuario,
            "email" => $email,
            "numero_random" => $numeroRandom
        ];
    }
    public function existeUsuario($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
        $resultado = $this->database->query($sql);

        // $resultado es un array o false, dependiendo cómo esté implementado tu métodoquery
        // Si es array, verificamos si tiene elementos (usuario encontrado)
        return !empty($resultado);
    }

    /*validacion mail*/
    public function buscarPorId($id)
    {
        return $this->database->query("SELECT * FROM usuarios WHERE id_usuario = '$id'");

    }

    public function marcarComoValidado($id)
    {
        return $this->database->execute("UPDATE usuarios SET estado = '1' WHERE id_usuario = '$id'");
    }



}