<?php

class ProponerController
{

    private $model;
    private $view;
    private $user;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
        $this->user = Security::getUser();
    }


}