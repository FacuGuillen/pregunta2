<?php
class ProfileGamerModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function traerLosdatosDelUsuarioYSuRanking($nombreJugador){
        return $this->database->query("SELECT u.nombre_usuario , r.puntaje_acumulado,r.cantidad_partidas
                                       FROM ranking r JOIN usuarios u ON r.id_ranking=u.tipo_ranking 
                                       WHERE u.nombre_usuario = '$nombreJugador'");
    }

}