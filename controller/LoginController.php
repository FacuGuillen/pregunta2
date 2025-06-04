<?php

class LoginController
{
    private $view;
    private $model;

    public function __construct($model,$view)
    {
        $this->model = $model;
        $this->view = $view;
    }


    public function show(){
        session_start();
        $userdata= $_SESSION['user'] ?? null;

        $context = [
            'userdata' => is_array($userdata) ? $userdata : false
        ];


        $this->view->render("login",$context);
    }

    public function validateUser() {
        session_start();
        $username = $_POST['nameuser'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->model->getUserByUsername($username);

        //lo comente porque hay algo con l alogico que me salta directo al else
        //  if ($user !=null && password_verify($password, $user['contrasena'])) {
        if($user['nombre'] == $username && $user['contrasena'] == $password) {
            $_SESSION["user"] = $user; // Guardamos todoel array del usuario
            $this->redirectTo("/Pregunta2/lobby/show");
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