<?php
require_once 'dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class AdministradorController{
    private $view;
    private $model;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;

    }

    public function show(){
        $filtro = $_GET['filtro'] ?? 'mes'; // "mes" o "año"
        $username = $_SESSION['user']['username'] ?? null;
        $cantidadUsuarios = $this->model->getJugadoresRegistrados($username,$filtro);
        $cantidadPartidasJugadas = $this->model->cantidadPartidasJugadas($filtro);
        $cantidadPreguntas = $this->model->cantidadPreguntasEnElJuego($filtro);
        $cantidadPreguntasCreadasPorUsuarios = $this->model->getPreguntasCreadasPorUsuarios($filtro);
        $cantidadUsuariosNuevos = $this->model->getUsuariosNuevos($filtro);

        $usuariosPorPais = $this->model->getUsuariosPorPais($filtro);
        $topUsuario = $this->model->getTopUsuarioPorcentajeCorrectas($filtro);
        $usuariosPorSexo = $this->model->getUsuariosPorSexo($filtro);
        $usuariosPorEdad = $this->model->getUsuariosPorEdad($filtro);
        $topUsuarioJson = json_encode($topUsuario);
        // Pasamos el JSON ya preparado para JS, sin escapes
        $usuariosPorPaisJson = json_encode($usuariosPorPais, JSON_UNESCAPED_UNICODE);

        $this->view->render("administrador", [
            "filtro" => $filtro,
            "isMes" => $filtro === 'mes',
            "isAño" => $filtro === 'año',
            "username" => $username,
            "cantidad_usuarios" => $cantidadUsuarios,
            "cantidad_partidas" => $cantidadPartidasJugadas,
            "cantidad_preguntas" => $cantidadPreguntas,
            "cantidad_preguntas_creadas_por_usuarios" => $cantidadPreguntasCreadasPorUsuarios,
            "cantidad_usuarios_nuevos" => $cantidadUsuariosNuevos,
            "usuarios_por_pais_json" => $usuariosPorPaisJson,
            "top_usuario" => $topUsuario,
            "top_usuario_json" => $topUsuarioJson,
            "usuarios_por_sexo_json" => json_encode($usuariosPorSexo, JSON_UNESCAPED_UNICODE),
            "usuarios_por_edad_json" => json_encode($usuariosPorEdad, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function generarReporte()
    {

        $tablaResumen = $_POST['tablaResumen'] ?? '';
        $filtro = $_POST['filtro'] ?? 'todos';
        $imgSexo = $_POST['imgSexo'] ?? '';
        $imgEdad = $_POST['imgEdad'] ?? '';
        $imgMapa = $_POST['imgMapa'] ?? '';

        $html = '
    <h1 style="text-align:center;">📊 Reporte Administrativo</h1>
    <p><strong>Filtro aplicado:</strong> ' . htmlspecialchars($filtro) . '</p>

    <h3>📌 Resumen general</h3>
    <div style="margin-bottom: 30px;">' . $tablaResumen . '</div>

    <h3>👥 Usuarios por sexo</h3>
    <img src="' . $imgSexo . '" style="width:auto; max-height:auto;" />

    <h3>🎂 Usuarios por grupo etario</h3>
    <img src="' . $imgEdad . '" style="width:auto; max-height:auto;" />

    <h3>🌍 Usuarios por país (mapa mundial)</h3>
    <img src="' . $imgMapa . '" style="width:auto; max-height:auto;" />

    <p style="text-align:center; font-size: 12px; margin-top: 30px;">Reporte generado automáticamente con Dompdf</p>
';

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("reporte_estadisticas.pdf", ["Attachment" => false]);
        exit;
    }


}