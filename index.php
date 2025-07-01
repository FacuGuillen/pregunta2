<?php
session_start();
require_once("core/Security.php");
require_once("Configuration.php");

$controller = strtolower($_GET["controller"] ?? '');
$method = $_GET["method"] ?? '';

$restricciones = [
    "editor" => [2],
    "administrador" => [3],
    "lobby" => [1,2,3],
    "juego" => [1],
    "listapartida" => [1],
    "partida" => [1],
    "profile" => [1, 2, 3],
    "profilegamer" => [1, 2, 3],
    "proponer" => [1],
    "ranking" => [1],
    "ruleta" => [1],
];


if (isset($restricciones[$controller])) {
    // Validar login
    if (!isset($_SESSION["user"])) {
        header("Location: /login/show");
        exit();
    }

    $tipoUsuario = $_SESSION["user"]["tipo_usuario"] ?? null;

    if (!in_array($tipoUsuario, $restricciones[$controller])) {
        header("Location: /login/show");
        exit();
    }
}

$configuration = new Configuration();
$router = $configuration->getRouter();
$router->go($controller, $method);
