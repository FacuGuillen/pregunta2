<?php
class PreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUserId($username){
     $id = $this->db->query("SELECT id_usuario FROM usuarios WHERE nombre_usuario = '$username'");
     return $id;
    }

    // Trae una pregunta aleatoria con sus respuestas
    public function getPreguntaAleatoria($userid) {
        $userid = (int)$userid;/*al valor lo hago int para que pueda buscarlo*/
        $pregunta = $this->db->getConnection()->query("
        SELECT p.id_pregunta, p.pregunta, c.categoria, c.color
        FROM pregunta p
        JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE NOT EXISTS (
            SELECT *
            FROM pregunta_usuarios pu 
            WHERE pu.id_pregunta = p.id_pregunta AND pu.id_usuario = $userid
        )
        ORDER BY RAND()
        LIMIT 1
        ")->fetch_assoc();

        if (!$pregunta) return null;

        $id_pregunta = $pregunta['id_pregunta'];

        $respuestas = $this->db->getConnection()->query("
        SELECT id_respuesta, respuesta 
        FROM respuesta 
        WHERE id_pregunta = $id_pregunta
        ORDER BY RAND()
"       )->fetch_all(MYSQLI_ASSOC);

        $pregunta['respuestas'] = $respuestas;

        return $pregunta;
    }

    // Verifica si una respuesta es correcta
    public function esCorrecta($id_respuesta) {
        $result = $this->db->getConnection()->query("
        SELECT es_correcta FROM respuesta WHERE id_respuesta = $id_respuesta
        ")->fetch_assoc();

        return $result['es_correcta'];
    }

    /*borrar el contenido de una tabla se se vio todas las preguntas
    SELECT *
            FROM pregunta_usuarios pu
            WHERE pu.id_pregunta = p.id_pregunta AND pu.id_usuario = $userid*/
}