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
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $idUsuario =(int) ($_SESSION["user"]["id_usuario"] ?? 0);

        if (!$categoria) {
            header("Location: /ruleta/show");
            exit;
        }

        $categoria = urldecode($categoria);

            $respuesta = $this->model->getPreguntaPorCategoria($categoria, $idUsuario);

            /*si no encuntra pregunta con esos filtros */
            if ($respuesta['status'] == 'no-preguntas-disponibles') {
                echo "<script>alert('Ya se vieron todas las preguntas de esa categoria. Buscando nueva categoria');</script>";
                $nuevaCategoria = $this->model->nuevaCategoriaDisponible($idUsuario);
                if ($nuevaCategoria) {
                    $respuesta = $this->model->getPreguntaPorCategoria($nuevaCategoria, $idUsuario);
                    $categoria = $nuevaCategoria;
                } else {

                    $this->model->borrarTodasPreguntasqueYaVioElUsuario($idUsuario);
                    //$this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
                    header("Location: /lobby/show");
                    //echo "<script>alert('ya se vieron todas las preguntas');</script>";
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
           // $this->model->guardarPreguntasUsuariosABorrar($idUsuario, $pregunta['id_pregunta']);
           // if ($exito) {
             //   echo "<script>alert('Pregunta guardada correctamente');</script>";
            //}else{
             //   echo "<script>alert('Error al guardar la pregunta');</script>";
            //}

        $pregunta['username'] = $username ?? null;
        $this->view->render("pregunta", $pregunta);
    }
    public function responder() {
        $idUsuario = (int) ($_SESSION["user"]["id_usuario"] ?? 0);
        $id_respuesta = $_POST['respuesta'] ?? null;
        $pregunta = $_SESSION['pregunta_actual'];

        if (!$id_respuesta) {
            // No respondió a tiempo
            $es_correcta = 0;
        } else {
            $es_correcta = $this->model->esCorrecta($id_respuesta);
        }

       // $this->model->guardarPreguntasQueElUsuarioContesto($idUsuario, $pregunta, $es_correcta);

        /*YA ENCONTRE EL PROBLEMA DE XQ NO ME INSERTA EN LA TABLA Y ES DEBIDO A QUE ME ESTABA GUARDI EL ID DEL
        USUARIO COM STRING EN VEZ DE COMO INT POR ESO FALLA
        SOLUCION  $username = $_SESSION["user"]["nameuser"] ?? null; USAR ESTO PERO PARRA QUE ME TRAIGA EL ID
        Y IMPLEMENTARLO EN CADA PARTE... YA QUE EL PROF DIJO QUE NO PODEMOS LLAMAR AL SEGURI EN CADA CLASE */
        if ($es_correcta) {
            $_SESSION['puntaje']++;
            header("Location: /juego/jugar");
        } else {
            header("Location: /juego/resultado");
        }
        exit;
    }
    public function resultado() {
        $username = $_SESSION["user"]["nameuser"] ?? null;
        $puntaje = $_SESSION['puntaje'] ?? 0;

        $guardarPartida = $this->model->guardarPartida($puntaje);
        $idUsuario = (int) ($_SESSION["user"]["id_usuario"] ?? 0);
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