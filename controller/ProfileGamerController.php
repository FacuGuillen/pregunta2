<?php

class ProfileGamerController{

    public $view;
    private $model;
    private $user;


    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::getUser();
    }

    public function show($nombreJugador = null){

        if ($nombreJugador == null) {
            header('location: /lobby/show');
            exit();
        }

        $username = $this->user['username'];

        $data = [
            "jugador" => $this->model->traerLosdatosDelUsuarioYSuRanking($nombreJugador)
        ] ;

        $context = array_merge($data, [
            'username' => $username
        ]);

        $this->view->render("profileGamer", $context);
    }
}