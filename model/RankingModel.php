<?php

class RankingModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }


    /*tener en cuenta que hay que crear una tabla de sea de ranking donde tendo el id usuario,el nuemro de puntaje ,ect*/
    public function getRanking(){
        return $this->database->query("SELECT 
            MAX(p.puntaje) as puntaje_maximo,
            CONCAT(UPPER(LEFT(u.nombre_usuario, 1)), LOWER(SUBSTRING(u.nombre_usuario, 2))) as nombre_usuario,
            u.foto_perfil as foto_perfil
        FROM partidas p 
        JOIN partidas_usuarios pu ON p.id_partidas = pu.id_partidas 
        JOIN usuarios u ON u.id_usuario = pu.id_usuario 
        GROUP BY u.id_usuario
        ORDER BY puntaje_maximo DESC");
    }
    /*SELECT MAX(p.puntaje) as puntaje, u.nombre_usuario as nombre_usuario, p.id_partidas as nro_partida FROM partidas p join partidas_usuarios pu on p.id_partidas=pu.id_partidas JOIN usuarios u on u.id_usuario=pu.id_usuario GROUP BY u.id_usuario;*/
}