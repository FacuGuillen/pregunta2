<?php
require_once __DIR__ . '/../dompdf/vendor/autoload.php'; // ajustá si tu path es distinto

use Dompdf\Dompdf;
use Dompdf\Options;

// Recibimos los datos del formulario
$filtro = $_POST['filtro'] ?? 'todos';
$imgSexo = $_POST['imgSexo'] ?? '';
$imgEdad = $_POST['imgEdad'] ?? '';

// Armamos el HTML a renderizar
$html = '
    <h1 style="text-align:center;">📊 Reporte Administrativo</h1>
    <p><strong>Filtro aplicado:</strong> ' . htmlspecialchars($filtro) . '</p>

    <h3>👥 Usuarios por sexo</h3>
    <img src="' . $imgSexo . '" style="width:100%; max-height:300px;" />

    <h3>🎂 Usuarios por grupo etario</h3>
    <img src="' . $imgEdad . '" style="width:100%; max-height:300px;" />

    <p style="text-align:center; font-size: 12px; margin-top: 30px;">Generado automáticamente con Dompdf</p>
';

// Configuramos Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // por si querés usar imágenes externas

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Mostramos en el navegador
$dompdf->stream("reporte_estadisticas.pdf", ["Attachment" => false]);
exit;
