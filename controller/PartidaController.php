<?php

class PartidaController
{
    private $model;
    private $view;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function show($idUsuario = 1)
    {
        $preguntas = $this->model->getPreguntas($partida = $this->model->crearPartida($idUsuario));

        $this->view->render("partida", [
            "partida" => $partida,
            "preguntas" => $preguntas
        ]);
    }

    public function responder()
    {
        $respuestasUsuario = $_POST["respuestas"];
        $partidaId = $_POST["partida_id"];
        $resultado = $this->model->verificarRespuesta($partidaId, $respuestasUsuario);
        $this->redirectTo("/partida/resultado?id=" . $partidaId);
    }

    public function resultado()
    {
        $partidaId = $_GET["id"];
        $resultado = $this->model->getResultado($partidaId);

        $this->view->render("partidaResultado", ["resultado" => $resultado]);
    }

    private function redirectTo($url)
    {
        header("Location: $url");
        exit();
    }
}
