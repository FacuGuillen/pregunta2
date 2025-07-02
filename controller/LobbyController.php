<?php
class LobbyController{
    private $view;
    public function __construct($view){
        $this->view = $view;
    }

    public function show() {

        $username = $_SESSION["user"]["nombre_usuario"] ?? null;

        $this->view->render("lobby", [
            "username" => $username
        ]);
    }

}