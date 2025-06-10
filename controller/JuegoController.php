<?php

class JuegoController
{
    private $view;
    private $model;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;

    }

    public function jugar(){
        $username = checkLogin();
        $pregunta = $this->model->getPreguntaAleatoria();

        if (!$pregunta) {
            $this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
            return;
        }

        $_SESSION['pregunta_actual'] = $pregunta['id_pregunta'];

        $pregunta['username'] = $username['nombre_usuario'] ?? null;


        $this->view->render("pregunta", $pregunta);
    }



    // Procesa la respuesta del usuario
    public function responder() {
        $username = checkLogin();
        $id_respuesta = $_POST['respuesta'];
        $id_pregunta = $_POST['id_pregunta'];

        $es_correcta = $this->model->esCorrecta($id_respuesta);

        if ($es_correcta) {
            $_SESSION['puntaje']++;
            header("Location: /Pregunta2/juego/jugar");
            exit;
        } else {
            header("Location: /Pregunta2/juego/resultado");
            exit;
        }
    }

    // Muestra el resultado final
    public function resultado() {
        $username = checkLogin();
        $puntaje = $_SESSION['puntaje'] ?? 0;

        $this->view->render("resultado", ['puntaje' => $puntaje,
        'username' => $username['nombre_usuario']]);
    }
}