<?php

class JuegoController
{
    private $view;
    private $model;
    const DURACION_PREGUNTA = 10;

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
        $preguntaExpirada = false;
        $respuestaSeleccionada = null;

        if (isset($_SESSION['pregunta_actual']) && isset($_SESSION['pregunta_inicio_tiempo'])) {
            $pregunta = $_SESSION['pregunta_actual'];
            $tiempoInicio = $_SESSION['pregunta_inicio_tiempo'];
            $tiempoTranscurrido = time() - $tiempoInicio;
            $tiempoRestante = self::DURACION_PREGUNTA - $tiempoTranscurrido;

            if ($tiempoRestante <= 0) {
                $preguntaExpirada = true;
                if (isset($pregunta['id_pregunta']) && !isset($_SESSION['respuesta_seleccionada_para_pregunta_' . $pregunta['id_pregunta']])) {
                    $this->model->guardarPreguntasQueElUsuarioContesto($idUsuario, $pregunta['id_pregunta'], 0);
                }
                unset($_SESSION['pregunta_actual']);
                unset($_SESSION['pregunta_inicio_tiempo']);
                unset($_SESSION['respuesta_seleccionada_para_pregunta_' . $pregunta['id_pregunta']]);

                header("Location: /juego/resultado?tiempo_agotado=1");
                exit;
            } else {
                $respuestaSeleccionada = $_SESSION['respuesta_seleccionada_para_pregunta_' . $pregunta['id_pregunta']] ?? null;
            }
        } else {
            $respuesta = $this->model->getPreguntaPorCategoria($categoria, $idUsuario);

            if (!isset($respuesta) || (isset($respuesta) && $respuesta['status'] !== 'ok')) {
                if (isset($respuesta['status']) && $respuesta['status'] == 'no-preguntas-disponibles') {
                    echo "<script>alert('Ya se vieron todas las preguntas de esa categoria. Buscando nueva categoria');</script>";
                    $nuevaCategoria = $this->model->nuevaCategoriaDisponible($idUsuario);
                    if ($nuevaCategoria) {
                        echo "<script>alert('Nueva categoria: $nuevaCategoria');</script>";
                        $respuesta = $this->model->getPreguntaPorCategoria($nuevaCategoria, $idUsuario);
                        $categoria = $nuevaCategoria;
                    } else {
                        $this->model->borrarTodasPreguntasqueYaVioElUsuario($idUsuario);
                        header("Location: /lobby/show");
                        exit();
                    }
                } elseif (isset($respuesta['status']) && $respuesta['status'] === 'repetida-muchas-veces') {
                    echo "<script>alert('pregunta respondida mas de 10 veces se busca por dificultad y categoria ');</script>";
                    $pregunta = $this->model->traerPreguntaClasificadaSegunLaDificultadUsuarioYCategoria($categoria, $idUsuario);
                }

                if (isset($respuesta['status']) && $respuesta['status'] === 'ok') {
                    $pregunta = $respuesta['pregunta'];
                } else if (!isset($pregunta) || !isset($pregunta['id_pregunta'])) {
                    echo "<script>alert('No se encontraron preguntas disponibles.');</script>";
                    $this->view->render("resultado", ['puntaje' => $_SESSION['puntaje'] ?? 0]);
                    return;
                }

                $_SESSION['pregunta_actual'] = $pregunta;
                $_SESSION['pregunta_inicio_tiempo'] = time();
                $this->model->guardarPreguntasQueYaVioElUsuario($idUsuario, $pregunta['id_pregunta']);
                $respuestaSeleccionada = null;
            } else {
                $pregunta = $respuesta['pregunta'];
                $_SESSION['pregunta_actual'] = $pregunta;
                $_SESSION['pregunta_inicio_tiempo'] = time();
                $this->model->guardarPreguntasQueYaVioElUsuario($idUsuario, $pregunta['id_pregunta']);
                $respuestaSeleccionada = null;
            }
        }

        // Prepare data for rendering the view
        $pregunta['username'] = $_SESSION["user"]['nombre_usuario'] ?? null;
        $pregunta['tiempo_restante'] = self::DURACION_PREGUNTA - (time() - $_SESSION['pregunta_inicio_tiempo']);
        if ($pregunta['tiempo_restante'] < 0) {
            $pregunta['tiempo_restante'] = 0;
            $pregunta['tiempo_expirado'] = 1;
        } else {
            $pregunta['tiempo_expirado'] = 0;
        }
        $pregunta['duracion_total'] = self::DURACION_PREGUNTA;
        $pregunta['respuesta_seleccionada'] = $respuestaSeleccionada;

        $this->view->render("pregunta", $pregunta);
    }

    public function registrarSeleccion() {
        header('Content-Type: application/json');

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $idPregunta = $data['id_pregunta'] ?? null;
        $idRespuestaSeleccionada = $data['id_respuesta_seleccionada'] ?? null;
        $preguntaActualEnSesion = $_SESSION['pregunta_actual']['id_pregunta'] ?? null;

        if (!$idPregunta || !$idRespuestaSeleccionada || $idPregunta != $preguntaActualEnSesion) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos o pregunta incorrecta.']);
            return;
        }

        $_SESSION['respuesta_seleccionada_para_pregunta_' . $idPregunta] = $idRespuestaSeleccionada;

        echo json_encode(['status' => 'ok', 'message' => 'Selección registrada.']);
    }

    public function responder()
    {
        $id_respuesta_enviada = $_POST['respuesta'] ?? null;
        $pregunta = $_SESSION['pregunta_actual'] ?? null;
        $idUsuario = (int)($_SESSION["user"]["id_usuario"] ?? null);
        $tiempoInicio = $_SESSION['pregunta_inicio_tiempo'] ?? null;

        $id_respuesta_previamente_seleccionada = null;
        if (isset($pregunta['id_pregunta'])) {
            $id_respuesta_previamente_seleccionada = $_SESSION['respuesta_seleccionada_para_pregunta_' . $pregunta['id_pregunta']] ?? null;
        }

        if (!$pregunta) {
            header("Location: /juego/jugar");
            exit;
        }

        $es_correcta = 0;

        $tiempoExpirado = false;
        if ($tiempoInicio !== null) {
            $tiempoTranscurrido = time() - $tiempoInicio;
            if ($tiempoTranscurrido > self::DURACION_PREGUNTA) {
                $tiempoExpirado = true;
            }
        } else {
            $tiempoExpirado = true;
        }

        $respuesta_final_a_evaluar = null;
        if ($tiempoExpirado) {
            $respuesta_final_a_evaluar = null;
        } else {
            $respuesta_final_a_evaluar = $id_respuesta_enviada ?? $id_respuesta_previamente_seleccionada;
        }

        if ($respuesta_final_a_evaluar !== null) {
            $es_correcta = $this->model->esCorrecta($respuesta_final_a_evaluar);
        } else {
            $es_correcta = 0;
        }

        $this->model->guardarPreguntasQueElUsuarioContesto($idUsuario, $pregunta['id_pregunta'], $es_correcta);

        unset($_SESSION['pregunta_actual']);
        unset($_SESSION['pregunta_inicio_tiempo']);
        unset($_SESSION['respuesta_seleccionada_para_pregunta_' . $pregunta['id_pregunta']]);

        if ($es_correcta) {
            $_SESSION['puntaje'] = ($_SESSION['puntaje'] ?? 0) + 1;
            header("Location: /juego/jugar");
            exit();
        } else {
            header("Location: /juego/resultado");
            exit();
        }
    }

    public function resultado()
    {
        $username = $_SESSION["user"]['nombre_usuario'] ?? null;
        $puntaje = $_SESSION['puntaje'] ?? 0;

        $guardarPartida = $this->model->guardarPartida($puntaje);
        $idUsuario = (int)($_SESSION["user"]["id_usuario"] ?? null);
        $idPartida = $guardarPartida;

        if ($idUsuario && $idPartida) {
            $this->model->guardarPartidaUsuario($idUsuario, $idPartida);
        }

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