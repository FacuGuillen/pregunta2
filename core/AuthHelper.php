<?php
function checkLogin() {
    session_start();

    if (!isset($_SESSION["user"])) {
        header("Location: " . BASE_URL . "login/show");
        exit();
    }

    return $_SESSION["user"]; // El usuario está logueado correctamente
}