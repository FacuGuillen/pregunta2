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

    public function perfilUsuario($username){
        $username = $this->user["username"];

        $userData = $this->model->getUser($username);

        $qr = $this->model->getQrByUsername($username);

        $context = array_merge($userData, [
            'username' => $username,
            'jugador'=> $userData,
            'qr' => $qr]);

        $this->view->render("profile", $context);
    }

}