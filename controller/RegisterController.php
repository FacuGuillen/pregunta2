<?php

class RegisterController{
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
            if ($_GET["error"] === "contrasena_no_coinciden") {
                $mensaje = "Las contraseñas no coinciden.";
            }
        }

        if (isset($_GET["success"])) {
            $tipo = "success";
            $mensaje = "¡Usuario registrado exitosamente!";
        }

        // Paso los datos a la vista
        $this->view->render("register", [
            "mensaje" => $mensaje,
            "tipo" => $tipo
        ]);
    }

    public function addUser()
    {
        if (empty($_POST["name"]) || empty($_POST["lastname"]) || empty($_POST["sex"]) ||
            empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["nameuser"])) {

            $this->redirectTo("/Pregunta2/register/show?error=campos_vacios");
            return;
        }

        if ($_POST["password"] != $_POST["confirm_password"]) {
            $this->redirectTo("/Pregunta2/register/show?error=contrasena_no_coinciden");
            return;
        }

        $data = [
            'name' => $_POST['name'],
            'lastname' => $_POST['lastname'],
            'sex' => $_POST['sex'],
            'date' => $_POST['date'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'nameuser' => $_POST['nameuser'],
            'photo' => $_POST['photo']
        ];

        if ($this->model->existeUsuario($data['nameuser'])) {
            $this->redirectTo("/Pregunta2/register/show?error=usuario_existente");
            return;
        }

        $resultado = $this->model->createUser($data);

        if ($resultado !== true) {
            // Si hubo error en la inserción, podrías redirigir con mensaje de error
            $this->redirectTo("/Pregunta2/register/show?error=error_bd");
            return;
        }

        $this->redirectTo("/Pregunta2/register/show?success=1");
    }

    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }
}