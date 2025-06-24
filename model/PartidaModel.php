<?php

class PartidaModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function crearPartida($id_usuario){
        //creamos la partida
        $this->database->execute("INSERT INTO partidas (puntaje) VALUES (0)");
        $result = $this->database->query("SELECT LAST_INSERT_ID()");
        $id_partida = $result[0]['LAST_INSERT_ID()'];

        //hacemos la union del usuario con la partida !!
        $this->database->execute("INSERT INTO partidas_usuarios (id_partida, id_usuario) VALUES ($id_partida, $id_usuario)");

        return $id_partida;
    }

    public function getPreguntas($id_partida){
        $sql = "SELECT p.*  
            FROM pregunta p  
            JOIN partida_pregunta pp ON p.id_pregunta = pp.id_pregunta  
            WHERE pp.id_partida = $id_partida";

        return $this->database->query($sql);
    }

    public function verificarRespuesta($id_pregunta, $id_respuesta){
        $sql = "SELECT es_correcta  
            FROM respuesta  
            WHERE id_pregunta = $id_pregunta AND id_respuesta = $id_respuesta";

        $resultado = $this->database->query($sql);

        return (!empty($resultado) && $resultado[0]['es_correcta'] == 1);
    }


}