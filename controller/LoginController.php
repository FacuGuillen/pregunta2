<?php

class LoginController{
    private $view;

    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show()
    {
        $mensaje = "";
        $tipo = ""; // Para saber si es success o error

        if (isset($_GET["error"])) {
            $tipo = "error";
            if ($_GET["error"] === "campos_vacios") {
                $mensaje = "Todos los campos son obligatorios.";
            }
            if ($_GET["error"] === "usuario_existente") {
                $mensaje = "El nombre de usuario ya existe.";
            }
        }

        if (isset($_GET["success"])) {
            $tipo = "success";
            $mensaje = "¡Usuario registrado exitosamente!";
        }

        // Paso los datos a la vista
        $this->view->render("login", [
            "mensaje" => $mensaje,
            "tipo" => $tipo
        ]);
    }

    public function add()
    {
        if (empty($_POST["nameUser"]) || empty($_POST["password"])) {
            $this->redirectTo("/Pregunta2/login/show?error=campos_vacios");
            return;
        }

        $nameUser = $_POST["nameUser"];
        $password = $_POST["password"];

        // Validación: evitar duplicados
        if ($this->model->existeUsuario($nameUser)) {
            $this->redirectTo("/Pregunta2/login/show?error=usuario_existente");
            return;
        }

        $this->model->add($nameUser, $password);
        $this->redirectTo("/Pregunta2/login/show?success=1");
    }

    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}