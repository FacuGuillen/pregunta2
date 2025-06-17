<?php

class RankingModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }


    /*tener en cuenta que hay que crear una tabla de sea de ranking donde tendo el id usuario,el nuemro de puntaje ,ect*/
    public function getRanking(){
        return $this->database->query("SELECT  u.nombre_usuario , u.tipo_ranking, r.puntaje_acumulado
                                       FROM ranking r JOIN usuarios u ON r.id_ranking = u.tipo_ranking
                                       ORDER BY r.puntaje_acumulado DESC");
    }

}