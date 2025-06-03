<?php
class LobbyController {
private $view;

public function __construct($view) {
$this->view = $view;
}

    public function show(){

    session_start();
    $userdata= $_SESSION['user'];

    $this->view->render("lobby",$userdata);
}
}