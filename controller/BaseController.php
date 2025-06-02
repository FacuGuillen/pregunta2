<?php

class BaseController
{

    protected $view;

    public function __construct($view){
        $this->view = $view;
    }

    public function render($viewFile,$data = []){
        session_start();

        if (isset($_SESSION["user"])) {
            $data['username'] = $_SESSION["user"]["nombre_usuario"] ?? null;
        }
        this->view->render($viewFile, $data);
    }
}