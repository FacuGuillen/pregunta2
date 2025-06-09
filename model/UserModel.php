<?php
class UserModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getUser($username){
        return $this->database->query("SELECT * FROM usuarios WHERE nombre = '$username'");
    }

    public function traerLosdatosDelUsuarioYSuRanking($username){
        return $this->database->query("SELECT u.nombre_usuario , r.puntaje_acumulado,r.cantidad_partidas
                                       FROM ranking r JOIN usuarios u ON r.id_ranking=u.tipo_ranking 
                                       WHERE u.nombre_usuario = '$username'");
    }

}