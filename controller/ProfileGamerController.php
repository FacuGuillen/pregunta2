<?php

class ProfileGamerController{

    public $view;
    private $model;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){

        $username = $_GET['usuario'];
        $user =  $this->model->getUser($username);


        $this->view->render("profileGamer", ["userdata" => $user]);
    }
}