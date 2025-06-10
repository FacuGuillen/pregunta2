<?php

require_once ("configuration/constants.php");

class indexController{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function show() {
        $username = checkLogin(); // <- Obligatorio antes de usar $_SESSION


        $this->view->render("index", [
            "username" => $username["nombre_usuario"]
        ]);
    }

    private function redirectTo($str)
    {
        header("Location: " . BASE_URL . $str);
        exit();
    }
}