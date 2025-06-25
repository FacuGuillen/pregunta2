<?php
class UserModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getUser($username){
        return $this->database->query("SELECT * FROM usuarios WHERE nombre = '$username'");
    }

    public function buscarJugadorPorId($idUsuario){
        return $this->database->query("SELECT * FROM usuarios WHERE id_usuario = '$idUsuario'");

    }

    public function traerLasPartidasDeUnUsuario($username)
    {  return $this->database->query("SELECT  p.fecha AS fecha,
                                              SUM(p.puntaje) AS puntaje_total
                                      FROM partidas p
                                      JOIN partidas_usuarios pu ON pu.id_partidas = p.id_partidas
                                      JOIN usuarios u ON u.id_usuario = pu.id_usuario
                                      WHERE u.nombre_usuario = '$username'
                                      GROUP by p.id_partidas");

    }

}