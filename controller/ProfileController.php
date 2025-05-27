<?php

class ProfileController{

   // private $model;
    private $view;

    public function __construct($view){
      //  $this->model = $model;
        $this->view = $view;
    }

    public function show(){
        /*mas adelante tenog que conectarla a la db y a la sesion
        $user = $_SESSION['user'];
        $data["usuario"] = $this->model->getUser($user);
        $this->view->render("profile,$data)*/

        $this->view->render("profile");
    }
}