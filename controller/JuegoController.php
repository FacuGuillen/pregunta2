<?php

class JuegoController
{
    private $view;
    private $model;


    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    public function jugar($categoria = null)
    {

        $idUsuario = ['id_usuario'];

        if (!$categoria) {
            header("Location: /ruleta/show");
            exit;
        }

        $categoria = urldecode($categoria);

            $respuesta = $this->model->getPreguntaPorCategoria($categoria, $idUsuario);

            if ($respuesta['status'] == 'no-preguntas-disponibles') {
                echo "<script>alert('Ya se vieron todas las preguntas de esa categoria. Buscando nueva categoria');</script>";
                $nuevaCategoria = $this->model->nuevaCategoriaDisponible($idUsuario);
                if ($nuevaCategoria) {
                    echo "<script>alert('Nueva categoria: $nuevaCategoria');</script>";
                    $respuesta = $this->model->getPreguntaPorCategoria($nuevaCategoria, $idUsuario);
                    $categoria = $nuevaCategoria;
                } else {

                    $this->model->borrarTodasPreguntasqueYaVioElUsuario($idUsuario);
                    //$this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
                    header("Location: /lobby/show");
                    exit();
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

    public function responder() {
       // $idUsuario = (int) ($_SESSION["user"]["id_usuario"] ?? 0);
        $id_respuesta = $_POST['respuesta'] ?? null;
        $pregunta = $_SESSION['pregunta_actual'];
        $idUsuario = ['id_usuario'];

        if (!isset($_POST['respuesta']) || !isset($_SESSION['pregunta_actual'])) {
            // Redirecciona con error
            header("Location: /juego/resultado");
            exit;
        }

        $id_respuesta = $_POST['respuesta'];
        $pregunta = $_SESSION['pregunta_actual'];

        if (!$id_respuesta) {
            // No respondió a tiempo
            $es_correcta = 0;
        } else {
            $es_correcta = $this->model->esCorrecta($id_respuesta);
        }

       // $this->model->guardarPreguntasQueElUsuarioContesto($idUsuario, $pregunta, $es_correcta);

        if ($es_correcta) {
            $_SESSION['puntaje']++;
            header("Location: /juego/jugar");
            exit();
        } else {
            header("Location: /juego/resultado");
            exit();
        }
    }


    public function resultado() {
        $username =['username'];
        $puntaje = $_SESSION['puntaje'] ?? 0;

        $guardarPartida = $this->model->guardarPartida($puntaje);
        $idUsuario = ['id_usuario'];
        $idPartida = $guardarPartida;

       // var_dump($idUsuario,$idPartida);
        //exit();
        $this->model->guardarPartidaUsuario($idUsuario, $idPartida);

        $this->view->render("resultado", ['puntaje' => $puntaje,
            'username' => $username
        ]);
        unset($_SESSION['puntaje']);
    }
}