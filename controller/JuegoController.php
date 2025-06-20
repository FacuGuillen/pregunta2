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

    public function jugar($categoria = null)
    {

        $idUsuario = $this->user['id_usuario'];

        if (!$categoria) {
            header("Location: /ruleta/show");
            exit;
        }

        $categoria = urldecode($categoria);

            $respuesta = $this->model->getPreguntaPorCategoria($categoria, $idUsuario);

            /*si no encuntra pregunta con esos filtros */
            if ($respuesta['status'] === 'no-preguntas-disponibles') {
                $nuevaCategoria = $this->model->nuevaCategoriaDisponible($idUsuario);
                echo "<script>alert('buscando otra categoria :" . addslashes($nuevaCategoria) . "');</script>";
                if ($nuevaCategoria) {
                    $respuesta = $this->model->getPreguntaPorCategoria($nuevaCategoria, $idUsuario);
                    $categoria = $nuevaCategoria;
                } else {
                    echo "<script>alert('ya se vieron todas las preguntas');</script>";
                    //$this->model->borrarTodasPreguntasqueYaVioElUsuario($idUsuario);
                    //$this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
                    header("Location: /lobby/show");
                }

            }

            if ($respuesta['status'] === 'repetida-muchas-veces') {
                echo "<script>alert('pregunta respondida mas de 10 veces se busca por dificultad y categoria ');</script>";
                $pregunta = $this->model->traerPreguntaClasificadaSegunLaDificultadUsuarioYCategoria($categoria, $idUsuario);
            }

            if ($respuesta['status'] === 'ok') {
                $pregunta = $respuesta['pregunta'];
            }

            if (!isset($pregunta) || !isset($pregunta['id_pregunta'])) {
                echo "<script>alert(' no se encontro preguntas');</script>";
                $this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
                return;
            }

            $_SESSION['pregunta_actual'] = $pregunta['id_pregunta'];
            $this->model->guardarPreguntasQueYaVioElUsuario($idUsuario, $pregunta['id_pregunta']);


        $pregunta['username'] = $this->user['nombre_usuario'] ?? null;
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