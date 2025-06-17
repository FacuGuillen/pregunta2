<?php

class ProfileGamerController{

    public $view;
    private $model;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show($nombreJugador = null){

        if ($nombreJugador == null) {
            header('location: /lobby/show');
            exit();
        }

        $username = Security::getUser();

        $data = [
            "jugador" => $this->model->traerLosdatosDelUsuarioYSuRanking($nombreJugador)
        ] ;

        $context = array_merge($data, ['username' => $username]);

        $this->view->render("profileGamer", $context);
    }
}