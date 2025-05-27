<?php
require_once("Configuration.php");
/*intento de redireccion de paginas*/
$configuration = new Configuration();
$router = $configuration->getRouter();

$router->go(
    $_GET["controller"] ,
    $_GET["method"]
);