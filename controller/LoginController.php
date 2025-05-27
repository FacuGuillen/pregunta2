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

<<<<<<< HEAD
=======
    public function validateUser() {
        $username = $_POST["nameuser"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->model->getUserByUsername($username);

        if ($user && password_verify($password, $user['contrasena'])) {
            session_start();
            $_SESSION["user"] = $user["nombre_usuario"];
            header("Location: /Pregunta2/index.php");
            exit;
        } else {
            $this->view->render("login", [
                "error" => "Credenciales incorrectas",
                "username" => $username
            ]);
        }
    }

>>>>>>> lau

}