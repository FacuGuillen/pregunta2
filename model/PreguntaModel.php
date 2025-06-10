<?php
class PreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDbConnection() {
        return $this->db->getConnection();
    }

    public function getPreguntaAleatoria() {
        $pregunta = $this->db->getConnection()->query("
        SELECT p.id_pregunta, p.pregunta, c.categoria, c.color
        FROM pregunta p
        JOIN categoria c ON p.id_categoria = c.id_categoria
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
}