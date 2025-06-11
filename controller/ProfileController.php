<?php

class ProfileController{

    private $model;
    private $view;
    /**
     * @var mixed|null
     */
    private $user;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::getUser();
    }

    public function show(){
        $this->view->render("profile", $this->user);
    }
}