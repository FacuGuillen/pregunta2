<?php
require_once ("core/Security.php");
class RuletaController{
    private $view;

    public function __construct($view){
        $this->view = $view;
        $this->user = Security::checkLogin();
    }

    public function show(){
        $this->view->render("ruleta", []);
    }
}