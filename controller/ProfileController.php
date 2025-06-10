<?php

class ProfileController{

    private $model;
    private $view;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::checkLogin();
    }

    public function show(){
        $username['username'] = $username['nombre_usuario'] ?? null;
        $this->view->render("profile", $username);
    }
}