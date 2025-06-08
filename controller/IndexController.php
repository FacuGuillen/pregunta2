<?php

require_once ("configuration/constants.php");

class indexController{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function show() {
        session_start(); //<- Obligatorio antes de usar $_SESSION

        $username = $_SESSION["user"]["nombre_usuario"] ?? null;

        $this->view->render("index", [
            "username" => $username
        ]);

    }

    private function redirectTo($str)
    {
        header("Location: " . BASE_URL . $str);
        exit();
    }
}