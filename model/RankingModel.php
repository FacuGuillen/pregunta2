<?php

class RankingModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }


    /*tener en cuenta que hay que crear una tabla de sea de ranking donde tendo el id usuario,el nuemro de puntaje ,ect*/
    public function getRanking(){
        return $this->database->query("SELECT u.nombre_usuario AS nombre_usuario, 
                                             SUM(p.puntaje) AS puntaje_acumulado 
                                      FROM partidas p JOIN partidas_usuarios pu ON pu.id_partidas = p.id_partidas 
                                                      JOIN usuarios u ON u.id_usuario = pu.id_usuario 
                                      GROUP by u.id_usuario 
                                      ORDER BY puntaje_acumulado DESC");
    }
}