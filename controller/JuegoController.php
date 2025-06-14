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

        $idUsuario = $this->user['id_usuario'];

        if (!$categoria) {
            header("Location: /ruleta/show");
            exit;
        }

        $categoria = urldecode($categoria);

        $pregunta = $this->model->traerPreguntaClasificadaSegunLaDificultadUsuarioYCategoria($categoria,$idUsuario);

        if (!$pregunta) {
            // alert que me diga que ya contestaste todas las preguntas de cierta categoria y va a buscar otra
            $nuevaCategoria = $this->model->nuevaCategoriaDisponible($idUsuario);

            if ($nuevaCategoria) {
                $pregunta = $this->model->traerPreguntaClasificadaSegunLaDificultadUsuarioYCategoria($nuevaCategoria,$idUsuario);
            } else {
                $this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
                //$this->model->borrarTodasPreguntasqueYaVioElUsuario($idUsuario);
                return;
            }

        }

        $_SESSION['pregunta_actual'] = $pregunta['id_pregunta'];

        $pregunta['username'] = $this->user['nombre_usuario'] ?? null;

        $this->model->guardarPreguntasQueYaVioElUsuario($idUsuario,$pregunta['id_pregunta']);

        $this->view->render("pregunta", $pregunta);
    }



    // Procesa la respuesta del usuario
    public function responder() {
        $idUsuario = $this->user['id_usuario'];
        $id_respuesta = $_POST['respuesta'];
        $pregunta =  $_SESSION['pregunta_actual'];

        $es_correcta = $this->model->esCorrecta($id_respuesta);
        /*guarda las preguntas y respuesta que el usuario ya vio y contesto */
        $this->model->guardarPreguntasQueElUsuarioContesto($idUsuario,$pregunta,$es_correcta);

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

        $guardarPartida = $this->model->guardarPartida($puntaje);
        $idUsuario = $this->user['id_usuario'];
        $idPartida = $guardarPartida;
        $guardarPartidaDeUsuario = $this->model->guardarPartidaUsuario($idUsuario, $idPartida);

        $this->view->render("resultado", ['puntaje' => $puntaje,
            'username' => $username
        ]);
        unset($_SESSION['puntaje']);
    }
}