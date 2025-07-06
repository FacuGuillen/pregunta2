<?php

class JuegoController
{
    private $view;
    private $model;


    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    public function jugar($categoria = null){
        $idUsuario = (int)($_SESSION["user"]["id_usuario"] ?? null);

        if (!$categoria) {
            header("Location: /ruleta/show");
            exit;
        }

        $categoria = urldecode($categoria);

        if (isset($_SESSION['pregunta_actual'])) {
            $pregunta =$_SESSION['pregunta_actual'];
        } else {
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

            $_SESSION['pregunta_actual'] = $pregunta;
            $this->model->guardarPreguntasQueYaVioElUsuario($idUsuario, $pregunta['id_pregunta']);
        }

        $pregunta['username'] = $_SESSION["user"]['nombre_usuario'] ?? null;
        $this->view->render("pregunta", $pregunta);
    }

    public function responder()
    {
        $id_respuesta = $_POST['respuesta'] ?? null;
        $pregunta = $_SESSION['pregunta_actual'] ?? null;
        $idUsuario = ['id_usuario'];

        if (!$pregunta) {
            header("Location: /juego/resultado");
            exit;
        }

        if (!$id_respuesta) {
            // No respondió a tiempo
            $es_correcta = 0;
        } else {
            $es_correcta = $this->model->esCorrecta($id_respuesta);
        }

        // Guardar la respuesta del usuario
        $this->model->guardarPreguntasQueElUsuarioContesto($idUsuario, $pregunta['id_pregunta'], $es_correcta);

        // Eliminar la pregunta actual para que cargue una nueva
        unset($_SESSION['pregunta_actual']);

        if ($es_correcta) {
            $_SESSION['puntaje']++;
            header("Location: /juego/jugar");
            exit();
        } else {
            header("Location: /juego/resultado");
            exit();
        }
    }


    public function resultado()
    {
        $username = ['username'];
        $puntaje = $_SESSION['puntaje'] ?? 0;

        $guardarPartida = $this->model->guardarPartida($puntaje);
        $idUsuario = ['id_usuario'];
        $idPartida = $guardarPartida;

        $this->model->guardarPartidaUsuario($idUsuario, $idPartida);

        $this->view->render("resultado", [
            'puntaje' => $puntaje,
            'username' => $username
        ]);

        unset($_SESSION['puntaje']);
    }

    public function reportar()
    {
        $pregunta = $_SESSION['pregunta_actual'] ?? null;

        if (!$pregunta) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Pregunta no encontrada']);
            return;
        }

        $this->model->reportarPregunta($pregunta['id_pregunta']);

        echo json_encode(['status' => 'ok', 'message' => 'Pregunta reportada con éxito']);
    }

}