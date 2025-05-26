<?php

class LoginController
{
    private $view;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){
        $this->view->render("login");
    }


}