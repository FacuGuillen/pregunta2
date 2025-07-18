<?php
class ProfileController {

    private $model;
    private $view;


    public function __construct($model, $view){
        $this->model = $model;
        $this->view = $view;
    }

    private function nombreCompletoPais($codigo) {
        $paises = [
            'ar' => 'Argentina',
            'bo' => 'Bolivia',
            'br' => 'Brasil',
            'cl' => 'Chile',
            'co' => 'Colombia',
            'ec' => 'Ecuador',
            'gy' => 'Guyana',
            'py' => 'Paraguay',
            'pe' => 'Perú',
            'sr' => 'Surinam',
            'uy' => 'Uruguay',
            've' => 'Venezuela'
        ];
        return $paises[strtolower($codigo)] ?? $codigo;
    }

    public function show($idUsuario = null){
        $userSession = $_SESSION["user"];
        $esPropio = false;

        if ($idUsuario === null) {
            $idUsuario = $userSession["id_usuario"];
            $esPropio = true;
        }

        $user = $this->model->getUserById($idUsuario);
        if (!$user) {
            header('location: /lobby/show');
            exit();
        }

        $userLocacion = $this->model->getUserLocacionById($idUsuario);

        $paisCodigo = $userLocacion['pais'] ?? null;
        $paisNombre = $this->nombreCompletoPais($paisCodigo);

        $this->view->render("profile", [
            "nombre" => $user['nombre'],
            "nombre_usuario" => $user['nombre_usuario'],
            "email" => $user['email'],
            "fecha_nacimiento" => $user['fecha_nacimiento'],
            "sexo" => $user['sexo'],
            "foto_perfil" => $user['foto_perfil'],
            "pais" => $paisNombre,
            "ciudad" => $userLocacion['ciudad'] ?? null,
            "latitud" => $userLocacion['latitud'] ?? null,
            "longitud" => $userLocacion['longitud'] ?? null,
            "es_propio" => $esPropio
        ]);
    }
}
