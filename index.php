<?php
session_start();
require_once("Configuration.php");

$controller = $_GET["controller"] ?? '';
$method = $_GET["method"] ?? '';

$controllerPublicos = ['login','register'];

if (!in_array($controller, $controllerPublicos)) {
    Security::checkLogin();
}

$configuration = new Configuration();
$router = $configuration->getRouter();

$router->go($controller, $method
);