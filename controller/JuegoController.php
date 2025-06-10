<?php

class JuegoController
{
    private $view;
    private $model;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::checkLogin();

    }

    public function jugar(){
        session_start();
        $userdata= $_SESSION["user"]; /*llama al usuairo de la session*/
        $username =  $userdata['nombre_usuario'];

        $userid =$this->model->getUserId($username);

       // $pregunta = $this->model->getPreguntaAleatoria($userid);

         public function jugar($categoria = null){
        $pregunta = $this->model->getPreguntaAleatoria();

        if (!$categoria) {
            // Si no se recibió categoría, redirigimos o mostramos un error
            header("Location: /ruleta/show");
            exit;
        }

        $pregunta = $this->model->getPreguntaPorCategoria($categoria);

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