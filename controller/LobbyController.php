<?php
class LobbyController{
    private $view;
    private $user;
    public function __construct($view)
    {
        $this->view = $view;
    }

    public function show() {
        $this->view->render("lobby", $this->user);
    }


    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}