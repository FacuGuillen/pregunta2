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

        $username = $_SESSION['user'] ;
        $this->view->render("profile", $username);
    }
}