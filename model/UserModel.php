<?php
class UserModel{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getUser($username){
        return $this->database->query("SELECT * FROM usuarios WHERE nombre = '$username'");
    }

    public function getUserById($id){
        $db = $this->database->getConnection();
        $id = $db->real_escape_string($id);
        $result = $db->query("SELECT * FROM usuarios WHERE id_usuario = '$id' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null; // no encontró usuario
    }


    public function getUserLocacionById($id){
        $db = $this->database->getConnection();
        $id = $db->real_escape_string($id);
        $sql = "SELECT u.*, r.pais, r.ciudad
        FROM usuarios u
        LEFT JOIN residencia r ON r.id_residencia = u.tipo_residencia
        WHERE u.id_usuario = '$id'
        LIMIT 1";
        $result = $db->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }





    public function traerLasPartidasDeUnUsuario($username)
    {  return $this->database->query("SELECT  p.fecha AS fecha,
                                              SUM(p.puntaje) AS puntaje_total
                                      FROM partidas p
                                      JOIN partidas_usuarios pu ON pu.id_partidas = p.id_partidas
                                      JOIN usuarios u ON u.id_usuario = pu.id_usuario
                                      WHERE u.nombre_usuario = '$username'
                                      GROUP by p.id_partidas");

    }

}