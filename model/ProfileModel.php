<?php

class ProfileModel {
    private $database;
    private $db;

    public function __construct($database){
        /*esta parte es la que conecta a la base de datos*/
        $this->database = $database;
        $this->db =$this->database->getConnection();
    }
    public function obtenerPerfil($username) {
        $stml = $this->db->prepare("
            SELECT u.nombre, u.apellido, u.sexo, u.email, u.nombre_usuario, u.fecha_nacimiento, u.foto_perfil,
                   r.pais AS tipo_residencia
            FROM usuarios u
            LEFT JOIN residencia r ON u.tipo_residencia = r.id_residencia
            WHERE u.nombre_usuario = ?
        ");
        if (!$stml) {
            die("Error en prepare: " . $this->db->error);
        }

        $stml->bind_param("s", $username);
        $stml->execute();
        $resultado = $stml->get_result();
        if (!$resultado) {
            die("Error en get_result: " . $stml->error);
        }
        return $resultado->fetch_assoc();
    }




}