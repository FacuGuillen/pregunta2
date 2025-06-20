<?php


class LoginController
{
    private $view;
    private $model;

    public function __construct($model, $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function show() {
        $username = $_SESSION["user"]["nameuser"] ?? null;

        $this->view->render("login", [
            "username" => $username,
        ]);
    }

    public function validateUser() {
        $username = $_POST["nameuser"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->model->getUserByUsername($username);

        if ($user && password_verify($password, $user['contrasena'])) {
        //if ($user['nombre_usuario'] === $username && $user['contrasena'] === $password) {
            $_SESSION["user"] = $user;

            $this->view->render("lobby", [
                "username" => $username
            ]);
        } else {
            $this->view->render("login", [
                "error" => "Credenciales incorrectas",
            ]);
        }
    }

    public function logout() {
        session_destroy(); // Destruir todos los datos de la sesión
        $this->view->render("lobby", []);
    }

    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}