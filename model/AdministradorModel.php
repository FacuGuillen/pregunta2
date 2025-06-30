<?php
class AdministradorModel{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDbConnection() {
        return $this->db->getConnection();
    }

    public function cantidadPartidasJugadas() {
        $sql = "SELECT COUNT(*) AS total FROM partidas_usuarios";
        $resultado = $this->getDbConnection()->query($sql);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }


    public function getJugadoresRegistrados(){
        $sql = "SELECT COUNT(*) AS total FROM usuarios where tipo_usuario = 1";
        $resultado = $this->getDbConnection()->query($sql);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;

    }


    public function cantidadPreguntasEnElJuego(){
        $sql = "SELECT COUNT(*) AS total FROM pregunta";
        $resultado = $this->getDbConnection()->query($sql);

        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }


    public function getUsuariosNuevos(){
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE fecha_creacion >= NOW() - INTERVAL 7 DAY;";
        $resultado = $this->getDbConnection()->query($sql);
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return (int) $fila['total'];
        }
        return 0;
    }



}