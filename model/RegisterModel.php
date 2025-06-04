<?php

class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function createUser($data,$files)
    {
        $db = $this->database->getConnection();

        $nombre = $db->real_escape_string($data['name']);
        $apellido = $db->real_escape_string($data['lastname']);
        $sexo = $db->real_escape_string($data['sex']);
        $fecha_nacimiento = $db->real_escape_string($data['date']);
        $email = $db->real_escape_string($data['email']);
        /*contraseña mas segura*/
        $contrasena = password_hash($data['password'], PASSWORD_DEFAULT);
        $nombre_usuario = $db->real_escape_string($data['nameuser']);

        /*guardar imagen*/
        //$foto_perfil = $db->real_escape_string($data['photo']);
        $foto_perfil = null;
        if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK){
            $nombreArchivo = basename($files['photo']['name']);
            $rutaDestino = __DIR__ . '/../public/imgs/' . $nombreArchivo;

            if (move_uploaded_file($files['photo']['tmp_name'], $rutaDestino)) {
                $foto_perfil = $db->real_escape_string($nombreArchivo);
            }else{
                return "error";
            }
    }

        /*insertar nuevo usuario*/
        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, fecha_nacimiento, email, contrasena, nombre_usuario, foto_perfil)
            VALUES ('$nombre', '$apellido', '$sexo', '$fecha_nacimiento', '$email', '$contrasena', '$nombre_usuario', '$foto_perfil')";

        if (!$db->query($sql)) {
            return $db->error;
        }

        return true;
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