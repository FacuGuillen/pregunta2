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
        session_start();
        $userdata= $_SESSION["user"]; /*llama al usuairo de la session*/
        $username =  $userdata['nombre_usuario'];

        $userid =$this->model->getUserId($username);

        $pregunta = $this->model->getPreguntaAleatoria($userid);

        if (!$pregunta) {
            $this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
            return;
        }

        $_SESSION['pregunta_actual'] = $pregunta['id_pregunta'];

        $this->view->render("pregunta", $pregunta);
    }



    // Procesa la respuesta del usuario
    public function responder() {
        session_start();
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
        session_start();
        $puntaje = $_SESSION['puntaje'] ?? 0;

        // Podés reiniciar sesión acá si querés que empiece de 0
        session_destroy();

        $this->view->render("resultado", ['puntaje' => $puntaje]);
    }
}