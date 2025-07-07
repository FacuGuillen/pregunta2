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
        $residencia = $db->real_escape_string($data['tipo_residencia']);

        /*generar numero random*/
        $numeroRandom = rand(10000, 99999);
        $estado = false;

        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, fecha_nacimiento, email, contrasena, nombre_usuario, foto_perfil,numero_random,estado, tipo_usuario, tipo_residencia)
            VALUES ('$nombre', '$apellido', '$sexo', '$fecha_nacimiento', '$email', '$contrasena', '$nombre_usuario', '$foto_perfil', '$numeroRandom', '$estado','$tipo_usuario', '$residencia')";

        if (!$db->query($sql)) {
            return false;
        }

        $idUsuario = $db->insert_id;

        return [
            "id_usuario" => $idUsuario,
            "nombre_usuario" => $nombre_usuario,
            "numero_random" => $numeroRandom,
            "email" => $email
        ];
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

    /*validacion mail*/
    public function buscarPorId($id)
    {
        return $this->database->query("SELECT * FROM usuarios WHERE id_usuario = '$id'");

    }
    public function marcarComoValidado($id)
    {
        return $this->database->execute("UPDATE usuarios SET estado = '1' WHERE id_usuario = '$id'");
    }

    /*ajax*/
    public function getUserByEmail($email){
        $stmt = $this->database->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        }



}