<?php
//header('Content-Type: application/json');

require_once "../model/RegisterModel.php";
require_once "../core/Database.php";

//conexion base de datos
$db = new Database('localhost', 'root', 'pregunta2', '');
$registerModel = new RegisterModel($db->getConnection());

if (!isset($_GET['email']) || empty($_GET['email'])) {
    echo json_encode(['error' => 'Falta el email']);
    exit;
}

$email = $_GET['email'];

$usuario = $registerModel->getUserByEmail($email);

if ($usuario){
    echo json_encode([
        'available' =>false,
        'nombre_usuario' => $usuario['nombre_usuario'] ?? ''
        ]);
}else{
    echo json_encode(['available' => true]);
}


/*foreach ($usuarios as $usuario) {
    if ($usuario['id'] === $email) {
        echo json_encode(['available' => false, 'nombre_usuario' => $usuario['name']]);
        exit;
    }
}

echo json_encode(['available' => true]);
*/