<?php

class JuegoController
{
    private $view;
    private $model;

    private $user;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::getUser();

    }

    public function jugar($categoria = null){
        //$pregunta = $this->model->getPreguntaAleatoria();

        if (!$categoria) {
            header("Location: /ruleta/show");
            exit;
        }

        $pregunta = $this->model->getPreguntaAleatoria($categoria);

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
        $id_respuesta = $_POST['respuesta'];
        $id_pregunta = $_POST['id_pregunta'];

        $es_correcta = $this->model->esCorrecta($id_respuesta);

        if ($es_correcta) {
            $_SESSION['puntaje']++;
            header("Location: /juego/jugar");
            exit;
        } else {
            header("Location: /juego/resultado");
            exit;
        }
    }

    // Muestra el resultado final
    public function resultado() {
        $username = $this->user['username'];
        $puntaje = $_SESSION['puntaje'] ?? 0;

        $this->view->render("resultado", ['puntaje' => $puntaje,
        'username' => $username['nombre_usuario']]);
    }
}