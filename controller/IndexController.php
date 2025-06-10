<?php

require_once ("configuration/constants.php");

class indexController{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function show() {

        $username = $_SESSION["user"]["nombre_usuario"] ?? null;

        $this->view->render("index", [
            "username" => $username
        ]);

    }

    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}