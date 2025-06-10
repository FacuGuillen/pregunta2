<?php
require_once("Configuration.php");
require_once("core/AuthHelper.php");

$configuration = new Configuration();
$router = $configuration->getRouter();

$router->go(
    $_GET["controller"] ,
    $_GET["method"]
);