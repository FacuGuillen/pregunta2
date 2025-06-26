<?php

class ProfileController{

    private $model;
    private $view;

    private $user;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::getUser();
    }

    public function show($idUsuario = null)
    {
        $username = $this->user['username'];

        // Si no hay ID → es el perfil propio
        if ($idUsuario === null) {
            $data = $this->user;
            $data['es_propio'] = true;
        } else {
            $jugador = $this->model->buscarJugadorPorId($idUsuario);
            $data = array_merge($jugador[0], [
                'es_propio' => false
            ]);
        }

        $data['username'] = $username;

        $this->view->render("profile", $data);
    }

}