<?php
class LobbyController{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function show() {
        $username = '';

        if (isset($_SESSION["user"]) && is_array($_SESSION["user"])) {
            $username = $_SESSION["user"]["nombre_usuario"] ?? '';
        }

        $this->view->render("lobby", [
            "username" => $username
        ]);
    }


    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}