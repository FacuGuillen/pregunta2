<?php

class RankingModel{

    private $database;

    public function __construct($database){
        $this->database = $database;
    }


    /*tener en cuenta que hay que crear una tabla de sea de ranking donde tendo el id usuario,el nuemro de puntaje ,ect*/
    public function getRanking(){
        return $this->database->query("SELECT u.nombre_usuario AS nombre_usuario,
                                              COUNT(pur.id_preguntas) AS cantidad_preguntas,
                                              SUM(pur.respuesta_correcta) AS puntaje_acumulado
                                       FROM usuarios u JOIN preguntas_usuarios_respuestas pur ON u.id_usuario = pur.id_usuario
                                       GROUP BY u.id_usuario
                                       ORDER BY puntaje_acumulado DESC");
    }
}