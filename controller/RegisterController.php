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
        $mensaje = "";
        $tipo = "";

        // Validación de campos vacíos
        if (empty($_POST["name"]) || empty($_POST["lastname"]) || empty($_POST["sex"]) ||
            empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["nameuser"])
            || empty($_POST["latitud"]) || empty($_POST["longitud"])) {

            $mensaje = "Todos los campos son obligatorios.";
            $tipo = "error";
            $this->view->render("register", compact("mensaje", "tipo"));
            return;
        }

        // Validación de contraseñas
        if ($_POST["password"] != $_POST["confirm_password"]) {
            $mensaje = "Las contraseñas no coinciden.";
            $tipo = "error";
            $this->view->render("register", compact("mensaje", "tipo"));
            return;
        }

        // Manejo de la imagen
        $nombreArchivo = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = uniqid() . "_" . basename($_FILES['photo']['name']);
            $destino = "public/uploads/" . $nombreArchivo;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destino)) {
                $mensaje = "Error al subir la imagen.";
                $tipo = "error";
                $this->view->render("register", compact("mensaje", "tipo"));
                return;
            }
        } else {
            $mensaje = "Debe subir una imagen.";
            $tipo = "error";
            $this->view->render("register", compact("mensaje", "tipo"));
            return;
        }

        $residenciaData = [
            'ciudad' => $_POST['ciudad'],
            'pais' => $_POST['pais'],
            'latitud' => $_POST['latitud'],
            'longitud' => $_POST['longitud']
        ];

        $idResidencia = $this->model->insertarResidencia($residenciaData);

        if (!$idResidencia) {
            $mensaje = "Error al guardar la residencia.";
            $tipo = "error";
            $this->view->render("register", compact("mensaje", "tipo"));
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
            'photo' => $nombreArchivo,
            'tipo_usuario' => 1,  // ← usuario tipo jugador por defecto
            'residencia' => $idResidencia
        ];

        if ($this->model->existeUsuario($data['nameuser'])) {
            $mensaje = "El nombre de usuario ya existe.";
            $tipo = "error";
            $this->view->render("register", compact("mensaje", "tipo"));
            return;
        }

        $resultado = $this->model->createUser($data);
        if ($resultado !== true) {
            $mensaje = "Error en la base de datos.";
            $tipo = "error";
            $this->view->render("register", compact("mensaje", "tipo"));
            return;
        }

        // Registro exitoso
        $mensaje = "¡Usuario registrado exitosamente!";
        $tipo = "success";
        $this->view->render("register", compact("mensaje", "tipo"));
    }

}