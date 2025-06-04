<?php

class RankingController{
    public $model;
    public $view;


    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){

        session_start();
        $userdata= $_SESSION['user'] ?? null;

        /*cambiar desues a ramking
        $data["usuarios"] = $this->model->getRanking();*/

        $data = ["usuarios" => $this->model->getRanking($userdata)];

        $context = array_merge($data,[
            'userdata' => $userdata,
        ]);


        $this->view->render('ranking',$context);
    }

}

/*ROLES
- USUARIO -> REPORTAR QUE PREGUNTA ES INVALIDA DESDE LA PANTALLA Y CREAR PREGUNTAS NUEVAS
- USUARIO EDITOR -> PERMITIR DAR DE ALTA ,BAJA Y MODIFICAR LAS PREGUNTAS
- USUARIO ADMINISTRADOR -> VER LA CANT DE JUGADARES, CANT DE PARTIDAS JUGADAS,CANT DE PREGUNTAS DEL JUEGO,CANT PREGUNTAS CREADAS,ETC
-

*/