<?php

class RankingModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getRanking(){
        return $this->database->query("
        SELECT  
            MAX(p.puntaje) AS puntaje_maximo,
            CONCAT(UPPER(LEFT(u.nombre_usuario, 1)), LOWER(SUBSTRING(u.nombre_usuario, 2))) AS nombre_usuario,
            u.foto_perfil
        FROM partidas p  
        JOIN partidas_usuarios pu ON p.id_partidas = pu.id_partidas  
        JOIN usuarios u ON u.id_usuario = pu.id_usuario  
        GROUP BY u.id_usuario, u.nombre_usuario, u.foto_perfil
        ORDER BY puntaje_maximo DESC
    ");
    }

}