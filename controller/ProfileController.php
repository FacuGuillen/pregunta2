<?php

class ProfileController{

    private $model;
    private $view;

    private $user;

    public function __construct($model,$view){
        $this->model = $model;
        $this->view = $view;

    }

    public function show(){
        $idUsuario = $_SESSION["user"]["id_usuario"] ?? null;
        if (!$idUsuario) {
            die("No hay usuario logueado.");
        }

        $user = $this->model->getUserById($idUsuario);
        if (!$user) {
            die("Usuario no encontrado.");
        }

        // Pasamos $idUsuario directamente
        $userLocacion = $this->model->getUserLocacionById($idUsuario);

        $this->view->render("profile", [
            "nombre" => $user['nombre'],
            "nombre_usuario" => $user['nombre_usuario'],
            "email" => $user['email'],
            "fecha_nacimiento" => $user['fecha_nacimiento'],
            "sexo" => $user['sexo'],
            "foto_perfil" => $user['foto_perfil'],
            // Si no hay locacion, mostrar valor por defecto para evitar errores
            "pais" => $userLocacion['pais'] ?? null,
            "ciudad" => $userLocacion['ciudad'] ?? null,
            "latitud" => $userLocacion['latitud'] ?? null,
            "longitud" => $userLocacion['longitud'] ?? null,
        ]);
    }


}