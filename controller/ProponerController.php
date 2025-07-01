<?php

class ProponerController
{

    private $model;
    private $view;


    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;

    }

    public function crearPregunta() {
        $categorias = $this->model->getCategorias();
        $this->view->render("crearPregunta", [
            'categorias' => $categorias,

        ]);
    }

    public function guardarPregunta() {
        $pregunta = $_POST['pregunta'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $correcta = $_POST['respuesta_correcta'] ?? '';
        $incorrecta1 = $_POST['respuesta_incorrecta1'] ?? '';
        $incorrecta2 = $_POST['respuesta_incorrecta2'] ?? '';
        $incorrecta3 = $_POST['respuesta_incorrecta3'] ?? '';

        $id_usuario = $this->user['id_usuario'] ?? null;

        if ($pregunta && $categoria && $correcta && $incorrecta1 && $incorrecta2 && $incorrecta3 && $id_usuario) {
            $this->model->guardarPreguntaPropuesta($pregunta, $categoria, $id_usuario);
            $idPregunta = $this->model->getLastInsertId();

            $this->model->guardarRespuestasPropuestas($idPregunta, $correcta, true);
            $this->model->guardarRespuestasPropuestas($idPregunta, $incorrecta1, false);
            $this->model->guardarRespuestasPropuestas($idPregunta, $incorrecta2, false);
            $this->model->guardarRespuestasPropuestas($idPregunta, $incorrecta3, false);

        }
    }
}