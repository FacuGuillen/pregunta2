<?php

class ProfileController{

    private $model;
    private $view;

    private $user;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show($idUsuario = null)
    {
        $username = $_SESSION["user"]["nombre_usuario"] ?? null;

        if ($idUsuario === null) {
            $data = $_SESSION["user"];
            $data['es_propio'] = true;
        } else{
            $jugador = $this->model->buscarJugadorPorId($idUsuario);

            if (empty($jugador) || empty($jugador[0])) {
                //scrip de ususario perfil inexistente
                header('location: /lobby/show');
                exit();
            }

            $data = array_merge($jugador[0], [
                'es_propio' => false
            ]);
        }

        $data['username'] = $username;

        $this->view->render("profile", $data);
    }

}