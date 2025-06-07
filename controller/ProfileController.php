<?php

class ProfileController{

    private $model;
    private $view;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;
    }

    public function show(){
        session_start();

        /*si quiero mostrar el puntaje alcanzado esto mas adelante voy a tener que usar la base de datos no me basta con la sesin*/
        $userdata= $_SESSION['user'];
        $context = [  // ->arreglo asociativo de mustache(clave=>valor)
            'foto_perfil' => $userdata['foto_perfil'] ?? 'default.png',
            'nombre_usuario' => $userdata['nombre_usuario'] ?? 'Invitado',
            'email' => $userdata['email'] ?? '',
            'nombre' => $userdata['nombre'] ?? '',
            'fecha_nacimiento' => $userdata['fecha_nacimiento'] ?? '',
            'sexo' => $userdata['sexo'] ?? '',
            'pais' => $userdata['pais'] ?? '',
            'ciudad' => $userdata['ciudad'] ?? '',
        ];

        $this->view->render("profile", $context);
    }
}