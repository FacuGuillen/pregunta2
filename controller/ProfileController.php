<?php

class ProfileController{

    private $model;
    private $view;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){
        session_start();

        /*si quiero mostrar el puntaje alcanzado esto mas adelante voy a tener que usar la base de datos no me basta con la sesin*/
        $userdata= $_SESSION['user'];

        $this->view->render("profile", $userdata);
    }
}