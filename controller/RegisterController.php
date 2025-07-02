<?php

class RegisterController{
    private $view;

    public function __construct($model, $view,$emailSender){
        $this->model = $model;
        $this->view = $view;
        $this->emailSender = $emailSender;

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
            if ($_GET["error"] === "email_no_enviado") {
                $mensaje = "El email no se pudo enviar";
            }
        }

        if (isset($_GET["success"])) {
            $tipo = "success";
            $mensaje = "¡Usuario registrado exitosamente!Valide su cuenta";
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
            'tipo_residencia' => $idResidencia
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


        //mandar el correo
        $body = $this->generarEmailBodyPara($resultado["id_usuario"],$resultado["nombre_usuario"],$resultado["numero_random"]);
        $exito = $this->emailSender->send($resultado["email"], $body);

        if ($exito) {
           $this->redirectTo("/register/show?success");
        } else{
            $this->redirectTo("/register/show?error=email_no_enviado");
        }
    }

    private function redirectTo($str)
    {
        header("Location: " . $str);
        exit();
    }

  /* Faltar Terminar y Validar */
    public function validar()
    {    $id = $_GET['idusuario'] ?? null;
        $codigo = $_GET['idverificador'] ?? null;

        if (!$id || !$codigo) {
            echo "Enlace inválido";
            return;
        }
        $usuarios = $this->model->buscarPorId($id);
        $usuario = $usuarios[0];

        if (!$usuario || $usuario['numero_random'] != $codigo) {
            echo "Verificación incorrecta";
            return;
        }

        // Actualizar a validado
        $this->model->marcarComoValidado($id);

        $this->redirectTo("/login/show?success=usuario_validado");

    }

    private function generarEmailBodyPara($id_usuario, $nombre_usuario, $numero_random)
    {
        return "<body>Hola $nombre_usuario, hacé click acá para validar tu cuenta: 
            <a href='http://localhost:8080/register/validar?idusuario=$id_usuario&idverificador=$numero_random'>
                Validar cuenta
            </a></body>";
    }




}