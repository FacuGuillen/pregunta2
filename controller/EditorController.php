<?php

require_once ("configuration/constants.php");

class EditorController{
    private $view;
    private $model;
    private $user;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;

        $this->user = Security::getUser();
    }

    public function show()
    {
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $preguntas = $this->model->getAllQuestions();

        $this->view->render("editor", [
            "username" => $username,
            "preguntas" => $preguntas
        ]);
    }

    public function filterCategory()
    {
        $username = $_SESSION["user"]["nameuser"] ?? null;

        $idCategoria = $_POST['id_categoria'] ?? '';

        if ($idCategoria !== '') {
            $preguntas = $this->model->getPreguntasPorIdCategoria((int)$idCategoria);
        } else {
            $preguntas = $this->model->getAllQuestions(); // sin filtro
        }

        $this->view->render("editor", [
            "username" => $username,
            "preguntas" => $preguntas
        ]);
    }

    public function editarPregunta(){
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $idPregunta = $_POST['id_pregunta'] ?? '';

        if ($idPregunta !== '') {
            $pregunta = $this->model->getPregunta($idPregunta);
            $respuestas = $this->model->getRespuestasPorPregunta($idPregunta);

            $this->view->render("editarPregunta", [
                "username" => $username,
                "pregunta" => $pregunta,
                "respuestas" => $respuestas
            ]);
        } /*else {
            $this->redirectTo('/editor/show');
        }*/
    }


    public function actualizarPregunta() {
        $username = $_SESSION["user"]["nameuser"] ?? null;

        $idPregunta = $_POST['id_pregunta'] ?? '';
        $preguntaTexto = $_POST['pregunta'] ?? '';

        $idRespuestas = $_POST['id_respuesta'] ?? [];
        $respuestasTexto = $_POST['respuesta'] ?? [];
        $idRespuestaCorrecta = $_POST['respuesta_correcta'] ?? '';

        // Validación básica
        if (!$idPregunta || !$preguntaTexto || empty($idRespuestas) || empty($respuestasTexto)) {
            $this->view->render("error", ["mensaje" => "Faltan datos para actualizar la pregunta."]);
            return;
        }

        // Actualizar la pregunta
        $this->model->actualizarPregunta($idPregunta, $preguntaTexto);

        // Actualizar cada respuesta
        foreach ($idRespuestas as $index => $idRespuesta) {
            $texto = $respuestasTexto[$index] ?? '';
            $esCorrecta = ($idRespuesta == $idRespuestaCorrecta) ? 1 : 0;
            $this->model->actualizarRespuesta($idRespuesta, $texto, $esCorrecta);
        }

        // Volver al perfil editor
        $preguntas = $this->model->getAllQuestions();
        $this->view->render("editor", [
            "username" => $username,
            "preguntas" => $preguntas,
            "mensaje" => "Pregunta actualizada correctamente"
        ]);
    }

    public function eliminarPregunta(){
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $idPregunta = $_POST['id_pregunta'] ?? '';

        if ($idPregunta !== '') {
            $this->model->eliminarPregunta((int)$idPregunta);
        }

        $preguntas = $this->model->getAllQuestions();

        $this->view->render("editor", [
            "username" => $username,
            "preguntas" => $preguntas,
            "mensaje" => "Pregunta eliminada correctamente"
        ]);
    }

    public function togglePregunta(){
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $idPregunta = $_POST['id_pregunta'] ?? '';
        if ($idPregunta !== '') {
            $this->model->pausarPregunta((int)$idPregunta);
        }
        $preguntas = $this->model->getAllQuestions();
        $this->view->render("editor", [
            "username" => $username,
            "preguntas" => $preguntas,
            "mensaje" => "Pregunta pausada correctamente"
        ]);
    }






}