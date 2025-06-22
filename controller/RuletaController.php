<?php
require_once ("core/Security.php");
class RuletaController{
    private $view;
    private $user;

    public function __construct($view){
        $this->view = $view;
        $this->user = Security::getUser();
    }

    public function show(){
        $this->view->render("ruleta", $this->user);
    }
}