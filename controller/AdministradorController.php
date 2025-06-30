<?php

class AdministradorController{
    private $view;
    private $model;
    private $user;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;

        $this->user = Security::getUser();
    }

    public function show(){
        $username = $_SESSION['user']['username'] ?? null;
        $cantidadUsuarios = $this->model->getJugadoresRegistrados($username);
        $cantidadPartidasJugadas = $this->model->cantidadPartidasJugadas();
        $cantidadPreguntas = $this->model->cantidadPreguntasEnElJuego();
        //$cantidadPreguntasCreadasPorUsuarios = $this->model->cantidadCreadas();
        $cantidadUsuariosNuevos = $this->model->getUsuariosNuevos();

        $this->view->render("administrador", [
            "username" => $username,
            "cantidad_usuarios" => $cantidadUsuarios,
            "cantidad_partidas" => $cantidadPartidasJugadas,
            "cantidad_preguntas" => $cantidadPreguntas,
            //"cantidad_preguntas_creadas_por_usuarios" => $cantidadPreguntasCreadasPorUsuarios,
            "cantidad_usuarios_nuevos" => $cantidadUsuariosNuevos
        ]);
    }

}