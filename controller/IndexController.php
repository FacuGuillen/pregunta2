<?php

require_once ("configuration/constants.php");

class indexController{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function show() {

        $this->view->render("index" );
    }


    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}