<?php
class ProfileGamerModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function traerLosdatosDelUsuarioYSuRanking($nombreJugador){
        return $this->database->query("SELECT SUM(p.puntaje) as puntaje_total , u.nombre_usuario as nombre_usuario ,COUNT(pu.id_usuario) as cant_partidas
                                       , u.email as email,u.id_usuario as id_usuario
                                       FROM `partidas` p 
                                           join partidas_usuarios pu on p.id_partidas=pu.id_partidas 
                                           join usuarios u on u.id_usuario=pu.id_usuario 
                                       WHERE u.nombre_usuario = '$nombreJugador'");
    }

}