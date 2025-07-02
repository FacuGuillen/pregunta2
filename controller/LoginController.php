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

        if (isset($_GET['success']) && $_GET['success'] === 'usuario_validado') {
            echo "<script>alert(' Cuenta verificada. Ya podés iniciar sesión.');</script>";
        }

        $this->view->render("login", ["username" => $username]);
    }

    public function validateUser() {
        $username = $_POST["nameuser"] ?? '';
        $password = $_POST["password"] ?? '';

        $user = $this->model->getUserByUsername($username);

        if ($user && password_verify($password, $user['contrasena'])) {
            $_SESSION["user"] = $user;

            if (Security::isAdmin()) {
                header("Location: /administrador/show");
                exit;
            } elseif (Security::isEditor()) {
                header("Location: /editor/show");
                exit;
            } elseif (Security::isPlayer()) {
                $this->view->render("lobby", [
                    "username" => $username,
                ]);
            } else {
                // En caso de que no tenga rol definido
                $this->view->render("login", [
                    "error" => "Rol no válido.",
                ]);
            }

        }

        if (!$user) {
            $error = "Usuario no encontrado";
        } elseif (!password_verify($password, $user['contrasena'])) {
            $error = "Contraseña incorrecta";
        } elseif ($user['estado'] == 0) {
            $error = "Tu cuenta no está validada.";
        } else {
            $error = "Error desconocido";
        }

        $this->view->render("login", [
            "error" => $error,
        ]);
    }


    public function logout() {
        session_destroy();
        header("Location: /lobby/show");
        exit();
    }

}