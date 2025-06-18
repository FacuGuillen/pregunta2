<?php

class RankingModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }


    /*tener en cuenta que hay que crear una tabla de sea de ranking donde tendo el id usuario,el nuemro de puntaje ,ect*/
    public function getRanking(){
        return $this->database->query("SELECT MAX(p.puntaje) as puntaje_maximo, u.nombre_usuario as nombre_usuario 
                                       FROM partidas p 
                                            JOIN partidas_usuarios pu on p.id_partidas=pu.id_partidas 
                                            JOIN usuarios u on u.id_usuario=pu.id_usuario 
                                       GROUP BY u.id_usuario");
    }
    /*SELECT MAX(p.puntaje) as puntaje, u.nombre_usuario as nombre_usuario, p.id_partidas as nro_partida FROM partidas p join partidas_usuarios pu on p.id_partidas=pu.id_partidas JOIN usuarios u on u.id_usuario=pu.id_usuario GROUP BY u.id_usuario;*/
}