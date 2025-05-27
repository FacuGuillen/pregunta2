<?php

class LoginController
{
    private $view;

    public function __construct($view)
    {
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

        if ($user && password_verify($password, $user['contrasena'])) {

            $_SESSION["user"] = $user; // Guardamos todo el array del usuario
            //header("Location: /Pregunta2/index.php");
            $this->redirectTo("/Pregunta2/index/show");
            exit;
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
        $this->redirectTo("/Pregunta2/index/show"); // Redirigir a donde quieras
    }

    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}