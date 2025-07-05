<?php

class AdministradorController{
    private $view;
    private $model;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;

    }

    public function show(){
        $username = $_SESSION['user']['username'] ?? null;
        $cantidadUsuarios = $this->model->getJugadoresRegistrados($username);
        $cantidadPartidasJugadas = $this->model->cantidadPartidasJugadas();
        $cantidadPreguntas = $this->model->cantidadPreguntasEnElJuego();
        $cantidadPreguntasCreadasPorUsuarios = $this->model->getPreguntasCreadasPorUsuarios();
        $cantidadUsuariosNuevos = $this->model->getUsuariosNuevos();

        $usuariosPorPais = $this->model->getUsuariosPorPais();
        $topUsuario = $this->model->getTopUsuarioPorcentajeCorrectas();
        $usuariosPorSexo = $this->model->getUsuariosPorSexo();
        $usuariosPorEdad = $this->model->getUsuariosPorEdad();
        $topUsuarioJson = json_encode($topUsuario);
        // Pasamos el JSON ya preparado para JS, sin escapes
        $usuariosPorPaisJson = json_encode($usuariosPorPais, JSON_UNESCAPED_UNICODE);

        $this->view->render("administrador", [
            "username" => $username,
            "cantidad_usuarios" => $cantidadUsuarios,
            "cantidad_partidas" => $cantidadPartidasJugadas,
            "cantidad_preguntas" => $cantidadPreguntas,
            "cantidad_preguntas_creadas_por_usuarios" => $cantidadPreguntasCreadasPorUsuarios,
            "cantidad_usuarios_nuevos" => $cantidadUsuariosNuevos,
            "usuarios_por_pais_json" => $usuariosPorPaisJson,
            "top_usuario" => $topUsuario,
            "top_usuario_json" => $topUsuarioJson,
            "usuarios_por_sexo_json" => json_encode($usuariosPorSexo, JSON_UNESCAPED_UNICODE),
            "usuarios_por_edad_json" => json_encode($usuariosPorEdad, JSON_UNESCAPED_UNICODE)
        ]);
    }



}