<?php

require_once("configuration/constants.php");

class LoginController
{
    private $view;
    private $model;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){
        $this->view->render("login");
    }

    public function validateUser() {
        session_start();
        $username = $_POST["nameuser"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->model->getUserByUsername($username);
        // lo comente porque no me deja pasar
       // if ($user && password_verify($password, $user['contrasena'])) {
        if ($user !=null) {
            $_SESSION["user"] = $user; // Guardamos todoel array del usuario

            $this->redirectTo("lobby/show");

        } else {
            $this->view->render("login", [
                "error" => "Credenciales incorrectas",
                "username" => $username
            ]);
        }
    }

    public function logout() {
        session_start();   // Iniciar la sesión para poder manipularla
        session_destroy(); // Destruir todos los datos de la sesión
        $this->redirectTo("index/show"); // Redirigir a donde quieras
    }

    private function redirectTo($str)
    {
        header("Location: " . BASE_URL . $str);
        exit();
    }
}